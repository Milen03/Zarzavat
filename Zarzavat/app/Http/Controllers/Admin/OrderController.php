<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller{
    //all orders
 public function index(Request $request)
    {
        $query = Order::latest();
        
        
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        $orders = $query->get();
        return view('admin.orders.index', compact('orders'));
    }

//details for current order
public function show(Order $order){

    $order->load('user','items.product');
    return view('admin.orders.show',compact('order'));
}


public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Статусът на поръчката е обновен успешно!');
    }


 public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Поръчката е изтрита.');
    }



}