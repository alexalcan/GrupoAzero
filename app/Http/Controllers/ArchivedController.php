<?php

namespace App\Http\Controllers;

use App\Log;
use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArchivedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        $texto = trim($request->busqueda);
        $fecha = $request->fecha;
        $fechaDos = $request->fechaDos;
        // dd($texto, $fecha);

        if ($texto == NULL && $fecha == NULL && $fechaDos == NULL){
            $orders = Order::where('delete', '1')->paginate(15);
            // $orders = Order::where('delete', NULL)->with('status')->get();
            $role = auth()->user()->role;
            $department = auth()->user()->department;
            return view('archived.index', compact('orders', 'role', 'department', 'texto', 'fecha'));
        }else{
            // Busqueda combinada
            if ($fecha && $texto && $fechaDos == NULL){
                $orders = Order::where('delete', '1')
                            ->where('invoice', 'LIKE', '%'.$texto.'%')
                            ->orWhere('invoice_number', 'LIKE', '%'.$texto.'%')
                            ->orWhere('client', 'LIKE', '%'.$texto.'%')
                            ->orWhere('office', 'LIKE', '%'.$texto.'%')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }
            if ($fecha && $texto && $fechaDos){
                $orders = Order::where('delete', '1')
                            ->where('invoice', 'LIKE', '%'.$texto.'%')
                            ->orWhere('invoice_number', 'LIKE', '%'.$texto.'%')
                            ->orWhere('client', 'LIKE', '%'.$texto.'%')
                            ->orWhere('office', 'LIKE', '%'.$texto.'%')
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);
            }
            // Busqueda por fecha
            if ($fecha && $texto == NULL && $fechaDos == NULL){
                $orders = Order::where('delete', '1')
                            ->whereDate('created_at', Carbon::parse($request->fecha)->toDateString())
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
                // dd($orders);
            }
            if ($fecha && $fechaDos && $texto == NULL){
                $orders = Order::where('delete', '1')
                            ->whereBetween('created_at', [Carbon::parse($request->fecha)->toDateString(), Carbon::parse($request->fechaDos)->toDateString()])
                            ->orderBy('created_at', 'asc')
                            ->paginate(1500);
                // dd($orders);
            }
            // Busqueda de ordenes
            if ($fecha == NULL && $fechaDos == NULL && $texto) {
                $orders = Order::where('delete', '1')
                            ->where('invoice', 'LIKE', '%'.$texto.'%')
                            ->orWhere('invoice_number', 'LIKE', '%'.$texto.'%')
                            ->orWhere('client', 'LIKE', '%'.$texto.'%')
                            ->orWhere('office', 'LIKE', '%'.$texto.'%')
                            ->orderBy('created_at', 'desc')
                            ->paginate(1500);
            }

            $role = auth()->user()->role;
            $department = auth()->user()->department;
            return view('archived.index', compact('orders', 'role', 'department', 'texto', 'fecha', 'fechaDos'));
        }

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $logs = Log::where('order_id', $id)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;
        // dd($order->purchaseorder);

        return view('archived.show', compact('order', 'role', 'department', 'logs'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::find($id);
        // dd($id, $order->status->name);
        $user = User::find(auth()->user()->id);

        $action = $user->name . ' desarchivó la orden';

        $order->update([
            'delete' => NULL,
            'updated_at' => now()
        ]);

        // Creación de un log
        Log::create([
            'status' => $order->status->name,
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        return redirect()->route('archived.index');
    }
}
