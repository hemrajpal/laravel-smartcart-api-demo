<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Product;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity'
    ];

    // Cart belongs to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cart item belongs to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
