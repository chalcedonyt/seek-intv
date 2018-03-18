<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function pricingRules()
    {
        return $this->hasMany(\App\Models\CustomerPricingRule::class);
    }
}
