<?php

namespace App\Http\Controllers;

use App\Log;
use App\Order;
use App\User;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $orders = Order::all();
        $orders = Order::where('delete', 1)->get();
        $role = auth()->user()->role;
        $department = auth()->user()->department;


        return view('orders.index', compact('orders', 'role', 'department'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        $user = User::find(auth()->user()->id);

        $action = $user->name . ' restableció la orden';

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

        return redirect()->route('orders.index');
    }
}
