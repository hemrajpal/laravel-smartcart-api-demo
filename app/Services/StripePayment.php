<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripePayment
{
    protected string $secret;

    public function __construct()
    {
        $this->secret = config('services.stripe.secret');
    }

    public function createPayment(Order $order)
    {
        Stripe::setApiKey($this->secret);

        $intent = PaymentIntent::create([
            'amount' => (int) ($order->total_amount * 100),
            'currency' => 'inr',

            'metadata' => [
                'order_id' => $order->id,
            ],
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'stripe',
            'gateway_transaction_id‎' => $intent->id,
            'amount' => $order->total_amount,
            'status' => 'pending',
        ]);

        return [
            'payment' => $payment,
            'client_secret' => $intent->client_secret,
        ];
    }
}
