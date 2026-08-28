<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Order;
use App\Models\Payment;
use App\Helpers\ApiResponse;
use Stripe\Stripe;
use Stripe\PaymentIntent;

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

        // Don't create payment for cancelled or completed orders
        if (in_array($order->status, ['cancelled', 'delivered'])) {
            return ApiResponse::error(
                'Payment cannot be created for this order',
                [],
                400
            );
        }

        // Check if payment already exists
        if ($order->payment) {
            return ApiResponse::error(
                'Payment already exists for this order',
                [],
                400
            );
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => (int) ($order->total_amount * 100),
            'currency' => 'inr',
            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            //'payment_method' => 'cod',
            'payment_method' => 'stripe',
            'amount' => $order->total_amount,
            'status' => 'pending',
            'gateway_transaction_id' => $intent->id,
        ]);

        /* $order->status = 'confirmed';
        $order->save(); */

        return ApiResponse::success(
            [
                'payment' => $payment,
                'client_secret' => $intent->client_secret,
            ], 
            
            'Payment initiated successfully'
        );
    }
}
