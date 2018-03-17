<?php

namespace Tests\Unit\Services\PricingRule\Rules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\AdType;
use App\Models\CheckoutItem;
use App\Services\PricingRule\Rules\FixedAdTypePriceWithMinQtyRule;

use Tests\Unit\Services\PricingRule\Rules\TestTraits\GeneratesCheckoutItemsOfAdType;

class FixedAdTypePriceWithMinQtyRuleTest extends TestCase
{
    use GeneratesCheckoutItemsOfAdType;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_validates_min_qty()
    {
        $adType = AdType::inRandomOrder()->first();
        $price = rand(1, 100);
        $minQty = rand(5, 10);

        $rule = new FixedAdTypePriceWithMinQtyRule;
        $rule->setFixedPrice($price);
        $rule->setMinQty($minQty);
        $rule->setAdType($adType);

        $checkoutItems = $this->generateCheckoutItems($adType, $minQty, $qtyOfDiffTypes = rand(6, 10));
        $this->assertTrue($rule->hasMinQty($checkoutItems->all()));

        $checkoutItems = $this->generateCheckoutItems($adType, $minQty+rand(1, 10), $qtyOfDiffTypes = rand(6, 10));
        $this->assertTrue($rule->hasMinQty($checkoutItems->all()));

        $checkoutItems = $this->generateCheckoutItems($adType, $minQty-rand(1, 10), $qtyOfDiffTypes = rand(6, 10));
        $this->assertFalse($rule->hasMinQty($checkoutItems->all()));
    }

    public function test_it_returns_correct_price()
    {

    }
}
