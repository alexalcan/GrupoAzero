<?php

namespace App\Http\Controllers;

use App\Log;
use App\Note;
use App\Order;
use App\Picture;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
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
        $hoy = date("Y-m-d H:i:s");
        $status = 6;
        $statusName = 'Entregado';
        $action = 'El pedido se entregó';

        if( $order->status->id == 7){
            $status = 7;
            $statusName = 'Cancelado';
            $action = 'Cancelación de pedido';
        }
        if( $order->status->id == 8 ){
            $status = 8;
            $statusName = 'Refacturación';
            $action = 'Refacturación de pedido';
        }

        $request->picture;
        $file = $request->file('picture');
        $name = $hoy . '-' . $order->invoice . '.' . $file->getClientOriginalExtension();
        $path = 'Images/' . $name;
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        Storage::putFileAs('/public/' . 'Images/', $file, $name );

        Picture::create([
            'picture' => $path,
            'user_id' => auth()->user()->id,
            'order_id' => $request->order_id
        ]);
        $action = $action . ', se subió un archivo de evidencia ';

        $order->update([
            'status_id' => $status,
        ]);

        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $request->order_id,
                'user_id' => auth()->user()->id,
                'created_at' => now()
            ]);
            $action = $action . ', se añadió una nota';
        }
        Log::create([
            'status' => $statusName,
            'action' => $action,
            'order_id' => $request->order_id,
            'user_id' => auth()->user()->id,
            'department_id' => auth()->user()->department->id,
            'created_at' => now()
        ]);

        // $orders = Order::all()->count();
        // $users = User::all()->count();
        // $logs = Log::all();

        return redirect()->route('orders.index');
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
