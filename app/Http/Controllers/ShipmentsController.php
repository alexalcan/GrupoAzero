<?php

namespace App\Http\Controllers;

use App\Log;
use App\Order;
use App\Shipment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShipmentsController extends Controller
{
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
        $action = 'Evidencia de material terminado';

        $name = str_replace(' ','-', $order->invoice .'-'.$file->getClientOriginalName());
        $path = 'Embarques/' . $name;
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        //if(Storage::putFileAs('/public/' . 'Embarques/', $file, $name )){
        if(Storage::putFileAs('public/Embarques/', $file, $name )){
            $shipment = Shipment::create([
                'file' => $path,
                'order_id' => $order->id,
                'created_at' => now(),
            ]);
            $action = $action . ', se subió evidencia con extensión '. $extension;
        }

        Log::create([
            'status' => 'Cancelado',
            'action' => $action,
            'order_id' => $order->id,
            'user_id' => $user->id,
            'department_id' => $user->department->id,
            'created_at' => now()
        ]);

        // $orders = Order::all();
        // $role = auth()->user()->role;
        // $department = auth()->user()->department;

        // return view('orders.index', compact('orders', 'role', 'department'));

        $role = auth()->user()->role;
        $department = auth()->user()->department;
        // dd($order->purchaseorder);
        $logs=[];

        return view('orders.show', compact('order', 'role', 'department','logs'));
    }

    public function shipmentEvidence(Request $request)
    {
        // dd($request->all());
        $order = Order::find($request->order);
        
       // $shipmentM = new Shipment();
       //var_dump($order->id);
        $shipments = Shipment::inOrder($order->id);
       // var_dump($shipments);
        //var_dump($shipments[0]);
        return view('shipments.evidence', compact('order', 'shipments'));
    }
}
