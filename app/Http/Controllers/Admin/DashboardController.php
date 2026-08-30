<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products'  => Product::count(),
            'orders'    => Order::count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'revenue'   => Order::whereNotIn('status', ['cancelled'])->sum('total'),
            'coupons'   => Coupon::where('is_active', true)->count(),
            'messages'  => ContactMessage::where('is_read', false)->count(),
        ];

        $recentOrders = Order::with('items')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
