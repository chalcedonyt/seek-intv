<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\TransferObjects\ResolvedPrice;

class ResolvedPriceTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(ResolvedPrice $resolvedPrice)
    {
        return [
            'price' => $resolvedPrice->price,
            'applied_pricing_rules' => $resolvedPrice->appliedPricingRules
        ];
    }
}
