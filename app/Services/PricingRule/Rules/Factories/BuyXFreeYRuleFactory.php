<?php
namespace App\Services\PricingRule\Rules\Factories;

use App\Models\AdType;
use App\Services\PricingRule\Rules\BuyXFreeYRule;

class BuyXFreeYRuleFactory
{
    /**
     * @param AdType $adType
     * @param integer $thresholdQty
     * @return BuyXFreeYRule
     */
    public static function create(AdType $adType, int $thresholdQty): BuyXFreeYRule
    {
        $rule = new BuyXFreeYRule();
        $rule->setAdType($adType);
        $rule->setThresholdQty($thresholdQty);
        return $rule;
    }

    /**
     * @param array $data
     * @return BuyXFreeYRule
     */
    public static function fromArray(array $data): BuyXFreeYRule
    {
        $adType = AdType::find($data['adTypeId']);
        return static::create($adType, $data['thresholdQty']);
    }
}
