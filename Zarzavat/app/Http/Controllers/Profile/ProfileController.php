<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Service\OrderServiceProfile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    protected $orderService;

    public function __construct(OrderServiceProfile $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        if (Auth::check()) {
            // За логнати потребители
            $orders = Order::where('user_id', Auth::id())
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // За гости - взимаме поръчките от сесията
            $guestOrders = session()->get('guest_orders', []);

            if (empty($guestOrders)) {
                $orders = collect(); // празна колекция
            } else {
                $orderIds = array_keys($guestOrders);
                $orders = Order::whereIn('id', $orderIds)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('profile.index', compact('orders'));
    }

    public function edit($orderId)
    {
        $order = $this->orderService->getOrder($orderId, ['items.product']);

        if (! $order) {
            return redirect()->route('profile.index')
                ->with('error', 'Поръчката не е намерена или нямате достъп до нея');
        }

        return view('profile.edit', compact('order'));
    }
}
