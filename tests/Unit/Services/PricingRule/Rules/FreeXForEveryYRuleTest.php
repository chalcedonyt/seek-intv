<?php

namespace Tests\Unit\Services\PricingRule\Rules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Services\PricingRule\Rules\FreeXForEveryYRule;
use App\Models\AdType;
use App\Models\CheckoutItem;

class FreeXForEveryYRuleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_resolves_correct_free_item_qtys()
    {
        $adType = AdType::inRandomOrder()->first();

        $rule = new FreeXForEveryYRule;
        $rule->setX(1);
        $rule->setY(4);
        $rule->setAdTypeId($adType->getKey());

        //8 eligible items in checkout, should return 2
        $checkoutItems = $this->generateCheckoutItems($adType, 8, 10);
        $this->assertEquals(2, $rule->numFreeItems($checkoutItems->all()));

        //9 eligible items in checkout, should return 2
        $checkoutItems = $this->generateCheckoutItems($adType, 9, 10);
        $this->assertEquals(2, $rule->numFreeItems($checkoutItems->all()));

        //3 eligible items in checkout, should return 0
        $checkoutItems = $this->generateCheckoutItems($adType, 9, 10);
        $this->assertEquals(2, $rule->numFreeItems($checkoutItems->all()));
    }

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
