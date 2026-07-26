<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\ApiResponse;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function orders(Request $request)
    {
        $orders = \App\Models\Order::with('payment')->latest()->paginate(10);
    
        return ApiResponse::success(
            $orders
        );
    }

    public function updateOrderStatus(Request $request, $orderId, $status)
    {
        $order = Order::where('id', $orderId)->first();
        if (!$order) {
            return ApiResponse::error('Order not found', [], 404);
        }

        $allowedStatuses = [
            'confirmed',
            'processing',
            'shipped',
            'delivered',
            'cancelled',
        ];

        if (!in_array($status, $allowedStatuses)) {
            return ApiResponse::error(
                'Invalid order status',
                [],
                400
            );
        }

        if ($order->status === 'delivered') {
            return ApiResponse::error(
                'Delivered order status cannot be changed',
                [],
                400
            );
        }

        if ($order->status === 'cancelled') {
            return ApiResponse::error(
                'Cancelled order status cannot be changed',
                [],
                400
            );
        }

        $order->status = $status;
        $order->save();

        return ApiResponse::success([], 'Order status has been updated.');
    }
}
