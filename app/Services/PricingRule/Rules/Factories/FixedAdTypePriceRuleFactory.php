<?php
namespace App\Services\PricingRule\Rules\Factories;

use App\Models\AdType;
use App\Services\PricingRule\Rules\FixedAdTypePriceRule;

class FixedAdTypePriceRuleFactory
{
    /**
     * @param AdType $adType
     * @param float $fixedPrice
     * @return FixedAdTypePriceRule
     */
    public static function create(AdType $adType, float $fixedPrice): FixedAdTypePriceRule
    {
        $rule = new FixedAdTypePriceRule();
        $rule->setAdType($adType);
        $rule->setFixedPrice($fixedPrice);
        return $rule;
    }

    /**
     * @param array $data
     * @return FixedAdTypePriceRule
     */
    public static function fromArray(array $data): FixedAdTypePriceRule
    {
        $adType = AdType::find($data['adTypeId']);
        return static::create($adType, $data['fixedPrice']);
    }
}
