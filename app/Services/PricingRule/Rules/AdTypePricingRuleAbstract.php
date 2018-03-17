<?php
namespace App\Services\PricingRule\Rules;

use App\Models\CheckoutItem;

abstract class AdTypePricingRuleAbstract
{
    protected $adTypeId;

    public function setAdTypeId(int $adTypeId)
    {
        $this->adTypeId = $adTypeId;
    }

    protected function checkoutItemIsOfAdType(CheckoutItem $item)
    {
        return $item->adType->getKey() == $this->adTypeId;
    }

    protected function itemsOfAdType(array $checkoutItems): array
    {
        return collect($checkoutItems)->filter(function (CheckoutItem $item) {
            return $item->adType->getKey() == $this->adTypeId;
        })->all();
    }
}