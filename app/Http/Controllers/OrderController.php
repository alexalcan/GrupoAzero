<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Log;
use App\Note;
use App\Order;
use App\Status;
use App\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( auth()->user()->department->name == 'Administrador' ){
            $orders = Order::all();
            $role = auth()->user()->role;
            $department = auth()->user()->department;
        }

        if( auth()->user()->department->name == 'Ventas' ){
            $orders = Order::where('status_id', 1)->get();
            $role = auth()->user()->role;
            $department = auth()->user()->department;
        }
        if( auth()->user()->department->name == 'Embarques' ){
            $orders = Order::where('status_id', 1)
                            ->orWhere('status_id', 2)
                            ->orWhere('status_id', 3)
                            ->orWhere('status_id', 4)
                            ->orWhere('status_id', 5)
                            ->get();
            $role = auth()->user()->role;
            $department = auth()->user()->department;
        }
        if( auth()->user()->department->name == 'Fabricación' ){
            $orders = Order::where('status_id', 2)
                            ->orWhere('status_id', 3)
                            ->get();
            $role = auth()->user()->role;
            $department = auth()->user()->department;
        }
        if( auth()->user()->department->name == 'Flotilla' ){
            $orders = Order::where('status_id', 5)->get();
            $role = auth()->user()->role;
            $department = auth()->user()->department;
        }



        return view('orders.index', compact('orders', 'role', 'department'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $orders = Order::all();
        $statuses = Status::all();
        $role = auth()->user()->role;
        $department = auth()->user()->department;

        return view('orders.create', compact('orders', 'statuses', 'role', 'department'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);
        $action = 'Creación de orden';

        if($request->credit){
            $credit = 1;
            $action = $action . ' a credito';
        }else{
            $credit = 0;
        }

        $order = Order::create([
            'invoice' => $request->invoice,
            'client' => $request->client,
            'credit' => $credit,
            'status_id' => $request->status_id,
            'created_at' => now(),
        ]);
        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $order->id,
                'user_id' => $user->id,
                'created_at' => now()
            ]);
            $action = $action . ',  se añadió una nota';
        }
        Log::create([
            'status' => $status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);


        $orders = Order::all();
        $role = auth()->user()->role;
        $department = auth()->user()->department;

        return view('orders.index', compact('orders', 'role', 'department'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        // dd($order->picture);

        return view('orders.show', compact('order', 'role', 'department'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::find($id);
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        $statuses = Status::all();

        $fav = Follow::where('user_id', auth()->user()->id)
        ->where('order_id', $order->id)
        ->first();

        return view('orders.edit', compact('order', 'statuses', 'role', 'department', 'fav'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $order = Order::find($id);
        $status = Status::find($request->status_id);
        $user = User::find(auth()->user()->id);

        $action = 'Actualización de orden';
        if($request->credit){
            $credit = 1;
        }else{
            $credit = 0;
        }
        if( $order->credit != $credit){
            if($request->credit){
                $credit = 1;
                $action = $action . ', orden a credito';
            }else{
                $credit = 0;
                $action = $action . ', orden sin credito';
            }
            // dd('Cambio a: '. $action);
        }
        $order->update($request->all());

        if($request->favorite){
            Follow::create([
                'user_id' => auth()->user()->id,
                'order_id' => $id
            ]);
        }

        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $order->id,
                'user_id' => auth()->user()->id
            ]);
            $action = $action . ', '.auth()->user()->name.' añadió una nota';
        }

        if($request->status_id != $request->statAnt){
            $status = Status::find($request->status_id);
            $user = User::find(auth()->user()->id);
            $action = $action . ', se actualizó estatus a "'. $status->name . '"';
            Note::create([
                'note' => $user->name . ' cambió el estatus a '. $status->name,
                'order_id' => $order->id,
                'user_id' => auth()->user()->id
            ]);
            if( $request->status_id == 7 || $request->status_id == 8 ){
                // dd('cancelar para refacturación.');
            }
        }
        Log::create([
            'status' => $status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        return redirect()->route('orders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
