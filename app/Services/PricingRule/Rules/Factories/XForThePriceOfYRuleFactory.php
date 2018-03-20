<?php
namespace App\Services\PricingRule\Rules\Factories;

use App\Models\AdType;
use App\Services\PricingRule\Rules\XForThePriceOfYRule;

class XForThePriceOfYRuleFactory
{
    /**
     * @param AdType $adType
     * @param integer $thresholdQty
     * @param integer $calculatedQty
     * @return XForThePriceOfYRule
     */
    public static function create(AdType $adType, int $thresholdQty, int $calculatedQty): XForThePriceOfYRule
    {
        $rule = new XForThePriceOfYRule();
        $rule->setAdType($adType);
        $rule->setThresholdQty($thresholdQty);
        $rule->setCalculatedQty($calculatedQty);
        return $rule;
    }

    /**
     * @param array $data
     * @return XForThePriceOfYRule
     */
    public static function fromArray(array $data): XForThePriceOfYRule
    {
        $adType = AdType::find($data['adTypeId']);
        return static::create($adType, $data['thresholdQty'], $data['calculatedQty']);
    }
}
