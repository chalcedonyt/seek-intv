<?php

namespace Tests\Unit\Services\PricingRule\Rules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\AdType;
use App\Models\CheckoutItem;
use App\TransferObjects\ResolvedPrice;
use App\Gateways\CheckoutItemCollection;
use App\Services\PricingRule\Rules\FixedAdTypePriceWithMinQtyRule;

use Tests\Unit\Services\PricingRule\Rules\TestTraits\GeneratesCheckoutItemsOfAdType;

class FixedAdTypePriceWithMinQtyRuleTest extends TestCase
{
    use GeneratesCheckoutItemsOfAdType, DatabaseTransactions;
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

        $checkoutItems = $this->generateCheckoutItems($adType, $minQty, $qtyOfDiffTypes = rand(6, 10))->all();
        $this->assertTrue($rule->hasMinQty($checkoutItems));

        $checkoutItems = $this->generateCheckoutItems($adType, $minQty+rand(1, 10), $qtyOfDiffTypes = rand(6, 10))->all();
        $this->assertTrue($rule->hasMinQty($checkoutItems));

        $checkoutItems = $this->generateCheckoutItems($adType, $minQty-rand(1, 10), $qtyOfDiffTypes = rand(6, 10))->all();
        $this->assertFalse($rule->hasMinQty($checkoutItems));
    }

    public function test_it_returns_correct_price_if_below_min_qty()
    {
        $adType = AdType::inRandomOrder()->first();
        $fixedPrice = rand(1000, 2000);
        $minQty = rand(6, 10);
        $qty = $minQty-1;

        $resolvedPrice = $this->resolveWithItemQty($adType, $fixedPrice, $minQty, $qty);
        $this->assertEquals(round($adType->price*$qty, 2), $resolvedPrice->price);
    }

    public function test_it_returns_correct_price_if_gte_min_qty()
    {
        $adType = AdType::inRandomOrder()->first();
        $fixedPrice = rand(1000, 2000);
        $minQty = rand(6, 10);
        $qty = $minQty+rand(0, 10);

        $resolvedPrice = $this->resolveWithItemQty($adType, $fixedPrice, $minQty, $qty);
        $this->assertEquals(round($fixedPrice*$qty, 2), $resolvedPrice->price);
    }

    /**
     * @param AdType $adType
     * @param float $fixedPrice
     * @param integer $minQty The minimum qty the rule should trigger at
     * @param integer $qty The number of checkout items generated
     * @return ResolvedPrice
     */
    protected function resolveWithItemQty(AdType $adType, float $fixedPrice, int $minQty, int $qty): ResolvedPrice
    {
        $rule = new FixedAdTypePriceWithMinQtyRule;
        $rule->setFixedPrice($fixedPrice);
        $rule->setMinQty($minQty);
        $rule->setAdType($adType);

        $checkoutItems = $this->generateCheckoutItems($adType, $qty)->all();

        $items = (new CheckoutItemCollection)
        ->setItems($checkoutItems)
        ->addRule($rule);

        return $items->resolve();
    }
}
