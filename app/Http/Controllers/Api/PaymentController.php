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
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;

use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function __construct(private PaymentService $paymentService)
    {
       
    }
    
    public function createCodPaymentOrder(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->first();
    
        if (!$order) {
            return ApiResponse::error('Order not found', [], 404);
        }
    
        // Don't create payment for cancelled or completed orders
        if (in_array($order->status, ['cancelled', 'delivered', 'confirmed'])) {
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
    
    
        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'cod',
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);
    
        $order->status = 'confirmed';
        $order->save();

        $order->user->notify(
            new \App\Notifications\OrderSuccessNotification($order)
        );
    
        return ApiResponse::success(
            [
                'payment' => $payment,
            ], 
            
            'Payment ordered successfully'
        );
    }

    public function createStripePaymentInitiate(Request $request, $orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->where('id', $orderId)
            ->first();

        if (!$order) {
            return ApiResponse::error('Order not found', [], 404);
        }

        // Prevent payment for orders that are already confirmed, delivered, or cancelled
        if (in_array($order->status, ['cancelled', 'delivered', 'confirmed'])) {
            return ApiResponse::error(
                'Payment cannot be created for this order',
                [],
                400
            );
        }        

        if ($order->payments()->where('status', 'paid')->exists()) {
            return ApiResponse::error(
                'Payment has already been completed for this order',
                [],
                400
            );
        }

        $response = $this->paymentService->createPaymentIntent($order);

        return ApiResponse::success(
            $response, 
            'Payment initiated successfully'
        );
    }
}
