<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller{
    //all orders
public function index(){

    $orders = Order::with('user','items.product')->get();
    return view('admin.orders.index',compact('orders'));
}

//details for current order
public function show(Order $order){

    $order->load('user','items.product');
    return view('admin.orders.show',compact('order'));
}


public function update(Request $request,Order $order){
 $request->validate([
            'status' => 'required|in:pending,processing,delivered',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.index')->with('success', 'Статусът е обновен.');

}

 public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Поръчката е изтрита.');
    }



}