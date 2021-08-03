<?php

namespace App\Http\Controllers;

use App\Cancelation;
use App\Evidence;
use App\Log;
use App\Order;
use App\Reason;
use App\Repayment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CancelationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        // dd($request->all());
        $order = Order::find($request->order_id);
        $user = User::find(auth()->user()->id);
        $request->file;
        $file = $request->file('file');
        $action = 'Cancelación de orden';

        if($request->type == 'evidence'){
            if( $request->oldCancelation == false){
                // dd('Nueva evicencia'); str_replace(' ','-', $hoy.'-'.$file->getClientOriginalName())
                // $name = $order->invoice;
                $name = str_replace(' ','-', $order->invoice .'-'.$file->getClientOriginalName());
                $path = 'Cancelaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);

                if(Storage::putFileAs('/public/' . 'Cancelaciones/', $file, $name )){
                    $cancelation = Cancelation::create([
                        'file' => $path,
                        'order_id' => $request->order_id,
                        'reason_id' => $request->reason_id,
                        'created_at' => now(),
                    ]);
                    Evidence::create([
                        'file' => $path,
                        'cancelation_id' => $cancelation->id
                    ]);
                    $action = $action . ', se subió un archivo con extensión '. $extension;
                }

                Log::create([
                    'status' => 'Cancelado',
                    'action' => $action,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'department_id' => $user->department->id,
                    'created_at' => now()
                ]);

            }else{
                // dd($request->all());
                $cancelation = Cancelation::find($request->cancelationId);
                $name = str_replace(' ','-', $order->invoice .'-'.$file->getClientOriginalName());
                $path = 'Cancelaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                if(Storage::putFileAs('/public/' . 'Cancelaciones/', $file, $name )){
                    Evidence::create([
                        'file' => $path,
                        'cancelation_id' => $cancelation->id
                    ]);
                    $action = $action . ', se subió nueva evidencia con extensión '. $extension;
                }
                Log::create([
                    'status' => 'Cancelado',
                    'action' => $action,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'department_id' => $user->department->id,
                    'created_at' => now()
                ]);
            }
        }
        if($request->type == 'repayment'){
            if( $request->oldCancelation == false){
                // dd('Nueva evicencia'); str_replace(' ','-', $hoy.'-'.$file->getClientOriginalName())
                // $name = $order->invoice;
                $name = str_replace(' ','-', $order->invoice .'-'.$file->getClientOriginalName());
                $path = 'Cancelaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);

                if(Storage::putFileAs('/public/' . 'Cancelaciones/', $file, $name )){
                    $cancelation = Cancelation::create([
                        'file' => $path,
                        'order_id' => $request->order_id,
                        'reason_id' => $request->reason_id,
                        'created_at' => now(),
                    ]);
                    Repayment::create([
                        'file' => $path,
                        'cancelation_id' => $cancelation->id
                    ]);
                    $action = $action . ', se subió un archivo con extensión '. $extension;
                }
                Log::create([
                    'status' => 'Cancelado',
                    'action' => $action,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'department_id' => $user->department->id,
                    'created_at' => now()
                ]);
            }else{
                // dd($request->all());
                $cancelation = Cancelation::find($request->cancelationId);
                $name = str_replace(' ','-', $order->invoice .'-'.$file->getClientOriginalName());
                $path = 'Cancelaciones/' . $name;
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                if(Storage::putFileAs('/public/' . 'Cancelaciones/', $file, $name )){
                    Repayment::create([
                        'file' => $path,
                        'cancelation_id' => $cancelation->id
                    ]);
                    $action = $action . ', se subió nueva evidencia con extensión '. $extension;
                }
                Log::create([
                    'status' => 'Cancelado',
                    'action' => $action,
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'department_id' => $user->department->id,
                    'created_at' => now()
                ]);
            }
        }
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
        //
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
        //
    }

    public function cancelEvidence(Request $request)
    {
        // dd($request->all());
        $order = Order::find($request->order);
        $reasons = Reason::all();

        return view('cancelations.evidence', compact('order', 'reasons'));
    }

    public function cancelRepayment(Request $request)
    {
        // dd($request->all());
        $order = Order::find($request->order);
        $reasons = Reason::all();

        return view('cancelations.repayment', compact('order', 'reasons'));
    }

}
