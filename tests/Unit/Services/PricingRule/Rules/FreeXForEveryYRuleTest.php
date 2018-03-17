<?php

namespace Tests\Unit\Services\PricingRule\Rules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\PricingRule\Rules\FreeXForEveryYRule;
use App\Models\AdType;
use App\Models\CheckoutItem;

use Tests\Unit\Services\PricingRule\Rules\TestTraits\GeneratesCheckoutItemsOfAdType;

class FreeXForEveryYRuleTest extends TestCase
{
    use GeneratesCheckoutItemsOfAdType, DatabaseTransactions;

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
        $rule->setAdType($adType);

        //8 eligible items in checkout, should return 2
        $checkoutItems = $this->generateCheckoutItems($adType, 8, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(2, $rule->numFreeItems($checkoutItems->all()));

        //9 eligible items in checkout, should return 2
        $checkoutItems = $this->generateCheckoutItems($adType, 9, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(2, $rule->numFreeItems($checkoutItems->all()));

        //3 eligible items in checkout, should return 0
        $checkoutItems = $this->generateCheckoutItems($adType, 3, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(0, $rule->numFreeItems($checkoutItems->all()));
    }
}
