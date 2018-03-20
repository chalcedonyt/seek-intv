<?php

namespace Tests\Unit\Services\PricingRule\Rules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Services\PricingRule\Rules\XForThePriceOfYRule;
use App\Models\AdType;
use App\Models\CheckoutItem;

use Tests\Unit\Services\PricingRule\Rules\TestTraits\GeneratesCheckoutItemsOfAdType;

class XForThePriceOfYRuleTest extends TestCase
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

        //3 for the price of 2
        $rule = new XForThePriceOfYRule;
        $rule->setThresholdQty(3);
        $rule->setCalculatedQty(2);
        $rule->setAdType($adType);

        //3 eligible items in checkout, should return 1
        $checkoutItems = $this->generateCheckoutItems($adType, 3, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(1, $rule->totalBonusQty($checkoutItems->all()));

        //4 eligible items in checkout, should return 1
        $checkoutItems = $this->generateCheckoutItems($adType, 4, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(1, $rule->totalBonusQty($checkoutItems->all()));

        //5 eligible items in checkout, should return 1
        $checkoutItems = $this->generateCheckoutItems($adType, 5, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(1, $rule->totalBonusQty($checkoutItems->all()));

        //6 eligible items in checkout, should return 2
        $checkoutItems = $this->generateCheckoutItems($adType, 6, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(2, $rule->totalBonusQty($checkoutItems->all()));

        //5 for the price of 4
        $rule = new XForThePriceOfYRule;
        $rule->setThresholdQty(5);
        $rule->setCalculatedQty(4);
        $rule->setAdType($adType);

        //3 eligible items in checkout, should return 0
        $checkoutItems = $this->generateCheckoutItems($adType, 3, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(0, $rule->totalBonusQty($checkoutItems->all()));

        //5 eligible items in checkout, should return 1
        $checkoutItems = $this->generateCheckoutItems($adType, 5, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(1, $rule->totalBonusQty($checkoutItems->all()));

        //6 eligible items in checkout, should return 1
        $checkoutItems = $this->generateCheckoutItems($adType, 6, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(1, $rule->totalBonusQty($checkoutItems->all()));

        //10 eligible items in checkout, should return 2
        $checkoutItems = $this->generateCheckoutItems($adType, 10, $qtyOfDiffTypes = rand(6, 10));
        $this->assertEquals(2, $rule->totalBonusQty($checkoutItems->all()));
    }
}
