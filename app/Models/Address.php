<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'postal_code',
        'is_default',
    ];   
    
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function orders()
    {
        return $this->hasMany(\App\Models\Address::class);
    }
}
