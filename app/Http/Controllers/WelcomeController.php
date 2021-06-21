<?php

namespace App\Http\Controllers;

use App\Log;
use App\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    public function search(Request $request)
    {
        // dd($request->all());

        if ( $order = Order::where('invoice', $request->invoice)
                        ->where('client', $request->client)
                        ->first() ){
            $message = 'Encontramos tu pedido, actualmente se encuentra en este estatus:';
            return view('welcome', compact('order', 'message'));
        }else{
            $message = 'Lo sentimos, no encontramos tu pedido, verifica tu número de factura';
            return view('welcome', compact('order', 'message'));
        }
    }
}
