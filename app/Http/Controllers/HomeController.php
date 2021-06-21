<?php

namespace App\Http\Controllers;

use App\Follow;
use App\Log;
use App\Order;
use App\Reason;
use App\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $orders = Order::all()->count();
        $users = User::all()->count();
        $logs = Log::orderBy('created_at','DESC')->get();

        return view('dashboard', compact('orders', 'users', 'logs'));
    }

    public function search(Request $request)
    {
        // dd($request->all());
        if($request->client){
            $order = Order::where('invoice', $request->invoice)
                        ->where('client', $request->client)
                        ->first();
            $follow = Follow::where('user_id', auth()->user()->id)
                        ->where('order_id', $order->id)
                        ->first();
            // dd($order->id, auth()->user()->id, $follow);
        }else{
            $order = Order::where('invoice', $request->invoice)->first();
            $follow = Follow::where('user_id', auth()->user()->id)
                        ->where('order_id', $order->id)
                        ->first();
            // dd($order->id, auth()->user()->id, $follow);
        }
        $orders = Order::all()->count();
        $users = User::all()->count();
        $logs = Log::orderBy('created_at','DESC')->get();
        // dd($order);

        return view('dashboard', compact('order', 'orders', 'users', 'logs', 'follow'));
    }

    public function picture(Request $request)
    {
        // dd($request->all());
        $order = Order::find($request->order);

        return view('upload', compact('order'));
    }

    public function cancelation(Request $request)
    {
        // dd($request->all());
        $order = Order::find($request->order);
        $reasons = Reason::all();

        return view('cancelation', compact('order', 'reasons'));
    }
}
