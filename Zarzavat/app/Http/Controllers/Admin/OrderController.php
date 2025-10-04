<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller{
    //all oreder
public function index(){

    $orders = Order::with('user','orederItems.product')->get();
    return view('admin.orders.index',compact('orders'));
}

//details for current order
public function show(Order $order){

    $order->load('user','orderItems.product');
    return view('admin.ordes.show',compact('order'));
}


public function update(Request $request,Order $order){
 $request->validate([
            'status' => 'required|in:pending,processing,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Статусът е обновен.');

}



}