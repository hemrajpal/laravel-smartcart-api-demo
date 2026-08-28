<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'amount',
        'currency',
        'status', //pending, failed, paid, refund
        'payment_method',
        'transaction_id',
        'gateway_transaction_id',
        'failed_reason',
        'paid_at',
        'failed_at',
        'refunded_at'
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
            'failed_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }

}
