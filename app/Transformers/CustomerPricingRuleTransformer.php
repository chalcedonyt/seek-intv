<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\CustomerPricingRule;

class CustomerPricingRuleTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['customer', 'pricingRule'];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(CustomerPricingRule $cpr): array
    {
        return [
            'id' => $cpr->getKey(),
            'display_name' => $cpr->display_name,
            'updated_at' => $cpr->updated_at,
            'pricing_rule_settings' => json_decode($cpr->pricing_rule_settings, true)
        ];
    }

    public function includeCustomer(CustomerPricingRule $cpr)
    {
        if ($cpr->customer) {
            return $this->item($cpr->customer, new CustomerTransformer);
        }
    }

    public function includePricingRule(CustomerPricingRule $cpr)
    {
        if ($cpr->pricingRule) {
            return $this->item($cpr->pricingRule, new PricingRuleTransformer);
        }
    }
}
