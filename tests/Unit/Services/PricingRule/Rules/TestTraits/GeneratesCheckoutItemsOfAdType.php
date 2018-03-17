<?php
namespace Tests\Unit\Services\PricingRule\Rules\TestTraits;

use App\Models\AdType;
use App\Models\CheckoutItem;

trait GeneratesCheckoutItemsOfAdType
{

    /**
     * @param AdType $adType
     * @param int $adTypeNum Number of items to generate for this ad type
     * @param int $diffAdTypeNum Number of items to generate for different ad type
     * @return Collection
     */
    protected function generateCheckoutItems(AdType $adType, int $adTypeNum, int $diffAdTypeNum): \Illuminate\Database\Eloquent\Collection
    {
        $eligibleItems = factory(CheckoutItem::class, $adTypeNum)
        ->make()
        ->each(function (CheckoutItem $item) use ($adType) {
            $item->adType = $adType;
        });

        $otherAdTypes = AdType::where('id', '<>', $adType->getKey())->get();
        $ineligibleItems = factory(CheckoutItem::class, $diffAdTypeNum)
        ->make()
        ->each(function (CheckoutItem $item) use ($otherAdTypes) {
           $item->adType = $otherAdTypes->random();
        });

        return $eligibleItems->concat($ineligibleItems);
    }
}