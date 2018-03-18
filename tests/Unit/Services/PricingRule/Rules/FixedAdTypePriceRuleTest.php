<?php

namespace Tests\Unit\Services\PricingRule\Rules;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\AdType;
use App\Models\CheckoutItem;
use App\TransferObjects\ResolvedPrice;
use App\Services\PricingRule\Rules\FixedAdTypePriceRule;
use App\Services\CheckoutItemCollection\CheckoutItemCollection;

use Tests\Unit\Services\PricingRule\Rules\TestTraits\GeneratesCheckoutItemsOfAdType;

class FixedAdTypePriceRuleTest extends TestCase
{
    use GeneratesCheckoutItemsOfAdType, DatabaseTransactions;

    public function test_it_returns_correct_price()
    {
        $adType = AdType::inRandomOrder()->first();
        $fixedPrice = rand(1000, 2000);
        $qty = rand(10, 20);

        $resolvedPrice = $this->resolveWithItemQty($adType, $fixedPrice, $qty);
        $this->assertEquals(round($fixedPrice*$qty, 2), $resolvedPrice->price);
    }

    /**
     * @param AdType $adType
     * @param float $fixedPrice
     * @param integer $minQty The minimum qty the rule should trigger at
     * @param integer $qty The number of checkout items generated
     * @return ResolvedPrice
     */
    protected function resolveWithItemQty(AdType $adType, float $fixedPrice, int $qty): ResolvedPrice
    {
        $rule = new FixedAdTypePriceRule;
        $rule->setFixedPrice($fixedPrice);
        $rule->setAdType($adType);

        $checkoutItems = $this->generateCheckoutItems($adType, $qty)->all();

        $items = (new CheckoutItemCollection)
        ->setItems($checkoutItems)
        ->addRule($rule);

        return $items->resolve();
    }
}
