<?php
namespace App\Services\PricingRule\Rules\Factories;

use App\Models\AdType;
use App\Services\PricingRule\Rules\FixedAdTypePriceWithMinQtyRule;

class FixedAdTypePriceWithMinQtyRuleFactory
{
    /**
     * @param AdType $adType
     * @param float $fixedPrice
     * @param integer $minQty
     * @return FixedAdTypePriceWithMinQtyRule
     */
    public static function create(AdType $adType, float $fixedPrice, int $minQty): FixedAdTypePriceWithMinQtyRule
    {
        $rule = new FixedAdTypePriceWithMinQtyRule();
        $rule->setAdType($adType);
        $rule->setFixedPrice($fixedPrice);
        $rule->setMinQty($minQty);
        return $rule;
    }

    /**
     * @param array $data
     * @return FixedAdTypePriceWithMinQtyRule
     */
    public static function fromArray(array $data): FixedAdTypePriceWithMinQtyRule
    {
        $adType = AdType::find($data['adTypeId']);
        return static::create($adType, $data['fixedPrice'], $data['minQty']);
    }
}
