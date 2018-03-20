<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class PricingRuleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(\App\Models\PricingRule $pr)
    {
        return [
            'display_name' => $pr->display_name,
            'provider_alias' => $pr->provider_alias
        ];
    }
}
