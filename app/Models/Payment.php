<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'status', //pending, paid, refund
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\Order::class);
    }

}
