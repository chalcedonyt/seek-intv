<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CustomerTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['pricingRules'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\Models\Customer $customer)
    {
        return [
            'id' => $customer->getKey(),
            'name' => $customer->name
        ];
    }

    public function includePricingRules(\App\Models\Customer $customer)
    {
        if ($customer->pricingRules) {
            return $this->collection($customer->pricingRules, new \App\Transformers\CustomerPricingRuleTransformer);
        }
    }
}
