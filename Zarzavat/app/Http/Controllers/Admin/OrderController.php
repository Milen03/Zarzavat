<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    // all orders
    public function index(Request $request) : View
    {
        $query = Order::query()->latest();

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        if ($request->boolean('archived')) {
            $query->onlyTrashed();
        }

        $orders = $query->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    // details for current order
    public function show(Order $order) : View
    {

        $order->load('user', 'items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order) : RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Статусът на поръчката е обновен успешно!');
    }

    public function destroy(Order $order) : RedirectResponse
    {
        $order->delete(); // soft delete
        return redirect()->route('admin.orders.index')->with('success', 'Поръчката е архивирана.');
    }

    public function restore(int $id): RedirectResponse
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();

        return redirect()->route('admin.orders.index', ['archived' => 1])
            ->with('success', 'Поръчката е възстановена.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->forceDelete();

        return redirect()->route('admin.orders.index', ['archived' => 1])
            ->with('success', 'Поръчката е окончателно изтрита.');
    }

}
