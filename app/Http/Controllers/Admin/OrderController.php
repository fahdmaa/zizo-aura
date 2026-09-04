<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $term = trim((string) $request->search);
            $like = \Illuminate\Support\Facades\DB::getDriverName() === 'pgsql' ? 'ilike' : 'like';
            $query->where(function ($q) use ($term, $like) {
                $q->where('customer_name', $like, '%'.$term.'%')
                  ->orWhere('customer_phone', 'like', '%'.$term.'%');

                if (is_numeric($term)) {
                    $q->orWhere('id', (int) $term);
                }
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $timestamps = [
            'confirmed'  => 'confirmed_at',
            'shipped'    => 'shipped_at',
            'delivered'  => 'delivered_at',
        ];

        $update = ['status' => $request->status];

        if (isset($timestamps[$request->status]) && ! $order->{$timestamps[$request->status]}) {
            $update[$timestamps[$request->status]] = now();
        }

        $order->update($update);

        return back()->with('success', 'Statut mis à jour : '.$order->fresh()->status_label.'.');
    }

    // Resource stubs required by --resource but not used
    public function create()  { abort(404); }
    public function store()   { abort(404); }
    public function edit()    { abort(404); }
    public function update()  { abort(404); }
    public function destroy() { abort(404); }
}
