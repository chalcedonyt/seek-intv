<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdType extends Model
{
    const TYPE_CLASSIC = 1;
    const TYPE_STANDOUT = 2;
    const TYPE_PREMIUM = 3;
}
