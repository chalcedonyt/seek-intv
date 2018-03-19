<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class AdTypeTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\Models\AdType $adType)
    {
        return $adType->toArray();
    }
}
