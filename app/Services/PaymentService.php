<?php

namespace App\Services;

use App\Models\Order;

class PaymentService
{
    public function __construct(
        protected $gateway
    ) {}

    public function createPaymentIntent(Order $order)
    {
        return $this->gateway->createPayment($order);
    }
}
