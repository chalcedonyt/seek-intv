<?php
namespace Tests\Unit\Services\PricingRule\Rules\TestTraits;

use App\Models\AdType;
use App\Models\Checkout;
use App\Models\CheckoutItem;

trait GeneratesCheckoutItemsOfAdType
{

    /**
     * @param AdType $adType
     * @param int $adTypeNum Number of items to generate for this ad type
     * @param int $diffAdTypeNum Number of items to generate for different ad type
     * @return Collection
     */
    protected function generateCheckoutItems(AdType $adType, int $adTypeNum, int $diffAdTypeNum = 0): \Illuminate\Database\Eloquent\Collection
    {
        $checkout = factory(Checkout::class)->create();

        $eligibleItems = factory(CheckoutItem::class, $adTypeNum)
        ->make()
        ->each(function (CheckoutItem $item) use ($adType, $checkout) {
            $item->adType()->associate($adType);
            $item->checkout()->associate($checkout);
        });

        $otherAdTypes = AdType::where('id', '<>', $adType->getKey())->get();
        $ineligibleItems = factory(CheckoutItem::class, $diffAdTypeNum)
        ->make()
        ->each(function (CheckoutItem $item) use ($otherAdTypes, $checkout) {
           $item->adType()->associate($otherAdTypes->random());
           $item->checkout()->associate($checkout);
        });

        return $eligibleItems->concat($ineligibleItems);
    }
}