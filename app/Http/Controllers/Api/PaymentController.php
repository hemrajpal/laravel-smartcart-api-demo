<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Payment;
use App\Helpers\ApiResponse;

class PaymentController extends Controller
{
    public function createPaymentOrder(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return ApiResponse::error('Order not found', [], 404);
        }

        // Check if payment already exists
        if ($order->payment) {
            return ApiResponse::error(
                'Payment already exists for this order',
                [],
                400
            );
        }

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'cod',
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);

        return ApiResponse::success(
            $payment,
            'Payment created successfully'
        );
    }
}
