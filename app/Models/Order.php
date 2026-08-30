<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_amount',
        'status', //pending, confirmed, processing, shipped, delivered, cancelled
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderItem::class);
    }

    public function address()
    {
        return $this->belongsTo(\App\Models\Address::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}
