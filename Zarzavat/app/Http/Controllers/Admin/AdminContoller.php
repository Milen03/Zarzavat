<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminContoller extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'productsCount' => Product::count(),
            'ordersCount' => Order::count(),
            'usersCount' => User::count(),
        ]);
    }
}
