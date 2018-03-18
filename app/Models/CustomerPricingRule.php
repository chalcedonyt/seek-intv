<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerPricingRule extends Model
{
    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class);
    }

    public function pricingRule()
    {
        return $this->belongsTo(\App\Models\PricingRule::class);
    }
}
