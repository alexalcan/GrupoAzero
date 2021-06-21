<?php

namespace App\Http\Controllers;

use App\Cancelation;
use App\Log;
use App\Order;
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
        $action = 'Cancelación de orden';

        $request->file;
        $file = $request->file('file');
        $name = $order->invoice;
        $path = 'Files/' . $name;
        if(Storage::putFileAs('/public/' . 'Files/', $file, $name )){
            Cancelation::create([
                'file' => $path,
                'order_id' => $request->order_id,
                'reason_id' => $request->reason_id,
                'created_at' => now(),
            ]);
            $action = $action . ', se subió evidencia';
        }

        Log::create([
            'status' => 'Cancelado',
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
}
