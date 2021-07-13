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

        $request->picture;
        $file = $request->file('picture');
        $name = $hoy . '-' . $order->invoice;
        $path = 'Images/' . $name;
        Storage::putFileAs('/public/' . 'Images/', $file, $name );

        Picture::create([
            'picture' => $path,
            'user_id' => auth()->user()->id,
            'deliveries_id' => NULL,
            'order_id' => $request->order_id
        ]);

        $order->update([
            'status_id' => 6,
        ]);

        if($request->note){
            Note::create([
                'note' => $request->note,
                'order_id' => $request->order_id,
                'user_id' => auth()->user()->id,
                'created_at' => now()
            ]);
        }
        Log::create([
            'status' => 'Entregado',
            'action' => 'Entrega de pedido',
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
