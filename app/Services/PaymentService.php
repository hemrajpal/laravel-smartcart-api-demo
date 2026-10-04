<?php

namespace App\Services;

use App\Models\Order;

interface PaymentService
{
    public function createPayment(Order $order): array;
}
