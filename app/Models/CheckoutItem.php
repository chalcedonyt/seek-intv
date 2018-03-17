<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutItem extends Model
{
    public function adType()
    {
        return $this->belongsTo(\App\Models\AdType::class);
    }

    public function checkout()
    {
        return $this->belongsTo(\App\Models\Checkout::class);
    }
}
