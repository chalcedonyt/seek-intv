<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\Models\Customer $customer)
    {
        return $customer->toArray();
    }
}
