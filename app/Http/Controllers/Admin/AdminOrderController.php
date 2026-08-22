<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = \App\Models\Order::with('payment')->latest()->paginate(10);
    
        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, \App\Models\Order $order)
    {
        $request->validate([
            'status' => [
                'required',
                'in:confirmed,processing,shipped,delivered,cancelled',
            ],
        ]);

        if ($order->status === 'delivered') {
            return back()->with(
                'error',
                'Delivered order status cannot be changed.'
            );
        }

        if ($order->status === 'cancelled') {
            return back()->with(
                'error',
                'Cancelled order status cannot be changed.'
            );
        }

        $order->status = $request->status;
        $order->save();

        return back()->with(
            'success',
            'Order status has been updated.'
        );
    }
}
