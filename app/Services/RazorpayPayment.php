<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;

class RazorpayPayment
{
    protected string $key;
    protected string $secret;

    public function __construct()
    {
        $this->key = config('services.razorpay.key');
        $this->secret = config('services.razorpay.secret');
    }

    public function createPayment(Order $order)
    {
        // Razorpay order creation will go here.

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'razorpay',
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);

        return [
            'payment' => $payment,
            'key' => $this->key,
        ];
    }
}
