<?php
namespace App\Services\PricingRule\Rules\Abstracts;

use App\Models\AdType;
use App\Models\CheckoutItem;

use App\Services\PricingRule\PricingRuleInterface;

abstract class AdTypePricingRuleAbstract extends PricingRuleAbstract
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

    public function toArray(): array
    {
        return [
            'adTypeId' => $this->adType->getKey()
        ];
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

    public static function fromArray(array $data): PricingRuleInterface
    {
        $new = parent::fromArray($data);
        $adType = AdType::find($data['adTypeId']);
        $new->setAdType($adType);
        return $new;
    }

}