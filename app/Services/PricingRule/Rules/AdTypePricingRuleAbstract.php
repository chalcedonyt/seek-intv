<?php
namespace App\Services\PricingRule\Rules;

use App\Models\AdType;
use App\Models\CheckoutItem;

abstract class AdTypePricingRuleAbstract
{
    /**
     * @var AdType
     */
    protected $adType;

    /**
     * @param AdType $adType
     * @return void
     */
    public function setAdType(AdType $adType)
    {
        $this->adType = $adType;
    }

    /**
     * Checks if the incoming CheckoutItem is the correct ad type for this rule
     * @param CheckoutItem $item
     * @return boolean
     */
    protected function checkoutItemIsOfAdType(CheckoutItem $item): bool
    {
        return $item->adType->getKey() == $this->adType->getKey();
    }

    /**
     * Filters the array of only items of the correct ad type
     *
     * @param array<CheckoutItem> $checkoutItems
     * @return array
     */
    protected function itemsOfAdType(array $checkoutItems): array
    {
        return collect($checkoutItems)->filter(function (CheckoutItem $item) {
            return $item->adType->getKey() == $this->adType->getKey();
        })->all();
    }
}