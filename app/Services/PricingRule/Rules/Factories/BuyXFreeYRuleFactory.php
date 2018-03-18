<?php
namespace App\Services\PricingRule\Rules\Factories;

use App\Models\AdType;
use App\Services\PricingRule\Rules\BuyXFreeYRule;

class BuyXFreeYRuleFactory
{
    /**
     * @param AdType $adType
     * @param integer $thresholdQty
     * @param integer $bonusQty
     * @return BuyXFreeYRule
     */
    public static function create(AdType $adType, int $thresholdQty, int $bonusQty): BuyXFreeYRule
    {
        $rule = new BuyXFreeYRule();
        $rule->setAdType($adType);
        $rule->setThresholdQty($thresholdQty);
        $rule->setBonusQty($bonusQty);
        return $rule;
    }

    /**
     * @param array $data
     * @return BuyXFreeYRule
     */
    public static function fromArray(array $data): BuyXFreeYRule
    {
        $adType = AdType::find($data['adTypeId']);
        return static::create($adType, $data['thresholdQty'], $data['bonusQty']);
    }
}
