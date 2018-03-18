<?php

namespace Tests\Feature\Services\PricingRule;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\AdType;
use App\Models\Checkout;
use App\Models\CheckoutItem;
use App\Models\Customer;
use App\Models\CustomerPricingRule;
use App\Models\PricingRule;

use App\Services\CheckoutItemCollection\CheckoutItemCollection;
use App\Services\CheckoutItemCollection\CheckoutItemCollectionFactory;

class CompositePricingRuleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @return void
     */
    public function test_default_customer()
    {
        $customer = factory(Customer::class)->create();
        $checkout = factory(Checkout::class)->create();

        $itemCollection = CheckoutItemCollectionFactory::createForCustomer($customer);

        $expectedPrice = 0;
        //assign items of every ad type
        AdType::all()->each(function (AdType $adType) use ($itemCollection, $checkout, &$expectedPrice) {
            factory(CheckoutItem::class, rand(2, 3))
            ->make()
            ->each(function (CheckoutItem $item) use ($adType, $itemCollection, $checkout, &$expectedPrice) {
                $item->checkout()->associate($checkout);
                $item->adType()->associate($adType);
                $itemCollection->addItem($item);
                $expectedPrice+= $adType->price;
            });
        });

        $resolvedPrice = $itemCollection->resolve();
        $this->assertEquals($resolvedPrice->price, $expectedPrice);
    }

    // Customer: default
    // ID added: `classic`, `standout`, `premium`
    // Total expected: $987.97
    public function test_intv_default_customer()
    {
        $customer = factory(Customer::class)->create();
        $checkout = factory(Checkout::class)->create();

        $itemCollection = CheckoutItemCollectionFactory::createForCustomer($customer);

        $expectedPrice = 987.97;
        //assign items of every ad type
        $this->addCheckoutItemToCollection(AdType::TYPE_CLASSIC, $itemCollection, $checkout, 1);
        $this->addCheckoutItemToCollection(AdType::TYPE_STANDOUT, $itemCollection, $checkout, 1);
        $this->addCheckoutItemToCollection(AdType::TYPE_PREMIUM, $itemCollection, $checkout, 1);

        $resolvedPrice = $itemCollection->resolve();
        $this->assertEquals($resolvedPrice->price, $expectedPrice);
    }

    // Customer: Unilever
    // SKUs Scanned: `classic`, `classic`, `classic`, `premium`
    // Total expected: $934.97
    public function test_intv_unilever_customer()
    {
        $customer = factory(Customer::class)->create([
            'name' => 'Mock unilever'
        ]);
        $threeForTwoRule = \App\Services\PricingRule\Rules\Factories\BuyXFreeYRuleFactory::fromArray([
            'adTypeId' => AdType::TYPE_CLASSIC,
            'bonusQty' => 1,
            'thresholdQty' => 2
        ]);
        CustomerPricingRule::unguard();
        $customerPricingRule = factory(CustomerPricingRule::class)->create([
            'customer_id' => $customer->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($threeForTwoRule->getAlias())
            ->first()
            ->getKey(),
            'pricing_rule_settings' => json_encode($threeForTwoRule->toArray())
        ]);
        $customer->pricingRules()->save($customerPricingRule);
        $checkout = factory(Checkout::class)->create();

        $itemCollection = CheckoutItemCollectionFactory::createForCustomer($customer);

        $expectedPrice = 934.97;

        $this->addCheckoutItemToCollection(AdType::TYPE_CLASSIC, $itemCollection, $checkout, 3);
        $this->addCheckoutItemToCollection(AdType::TYPE_PREMIUM, $itemCollection, $checkout, 1);

        $resolvedPrice = $itemCollection->resolve();
        $this->assertEquals($resolvedPrice->price, $expectedPrice);
    }

    // Customer: Apple
    // SKUs Scanned: `standout`, `standout`, `standout`, `premium`
    // Total expected: $1294.96
    public function test_intv_apple_customer()
    {
        $customer = factory(Customer::class)->create([
            'name' => 'Mock Apple'
        ]);
        $standoutPriceRule = \App\Services\PricingRule\Rules\Factories\FixedAdTypePriceRuleFactory::fromArray([
            'adTypeId' => AdType::TYPE_STANDOUT,
            'fixedPrice' => 299.99
        ]);
        CustomerPricingRule::unguard();
        $customerPricingRule = factory(CustomerPricingRule::class)->create([
            'customer_id' => $customer->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($standoutPriceRule->getAlias())
            ->first()
            ->getKey(),
            'pricing_rule_settings' => json_encode($standoutPriceRule->toArray())
        ]);
        $customer->pricingRules()->save($customerPricingRule);
        $checkout = factory(Checkout::class)->create();

        $itemCollection = CheckoutItemCollectionFactory::createForCustomer($customer);

        $expectedPrice = 1294.96;

        $this->addCheckoutItemToCollection(AdType::TYPE_STANDOUT, $itemCollection, $checkout, 3);
        $this->addCheckoutItemToCollection(AdType::TYPE_PREMIUM, $itemCollection, $checkout, 1);

        $resolvedPrice = $itemCollection->resolve();
        $this->assertEquals($resolvedPrice->price, $expectedPrice);
    }

    // Customer: Nike
    // SKUs Scanned: `premium`, `premium`, `premium`, `premium`
    // Total expected: $1519.96
    public function test_intv_nike_customer()
    {
        $customer = factory(Customer::class)->create([
            'name' => 'Mock Nike'
        ]);
        $standoutPriceRule = \App\Services\PricingRule\Rules\Factories\FixedAdTypePriceWithMinQtyRuleFactory::fromArray([
            'adTypeId' => AdType::TYPE_PREMIUM,
            'fixedPrice' => 379.99,
            'minQty' => 4
        ]);
        CustomerPricingRule::unguard();
        $customerPricingRule = factory(CustomerPricingRule::class)->create([
            'customer_id' => $customer->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($standoutPriceRule->getAlias())
            ->first()
            ->getKey(),
            'pricing_rule_settings' => json_encode($standoutPriceRule->toArray())
        ]);
        $customer->pricingRules()->save($customerPricingRule);
        $checkout = factory(Checkout::class)->create();

        $itemCollection = CheckoutItemCollectionFactory::createForCustomer($customer);

        $expectedPrice = 1519.96;

        $this->addCheckoutItemToCollection(AdType::TYPE_PREMIUM, $itemCollection, $checkout, 4);

        $resolvedPrice = $itemCollection->resolve();
        $this->assertEquals($resolvedPrice->price, $expectedPrice);
    }

    public function test_intv_ford_customer()
    {
        $customer = factory(Customer::class)->create([
            'name' => 'Mock Ford'
        ]);
        $classicPriceRule = \App\Services\PricingRule\Rules\Factories\BuyXFreeYRuleFactory::fromArray([
            'adTypeId' => AdType::TYPE_CLASSIC,
            'bonusQty' => 1,
            'thresholdQty' => 4
        ]);
        $standoutPriceRule = \App\Services\PricingRule\Rules\Factories\FixedAdTypePriceRuleFactory::fromArray([
            'adTypeId' => AdType::TYPE_STANDOUT,
            'fixedPrice' => 309.99
        ]);
        $premiumPriceRule = \App\Services\PricingRule\Rules\Factories\FixedAdTypePriceWithMinQtyRuleFactory::fromArray([
            'adTypeId' => AdType::TYPE_PREMIUM,
            'fixedPrice' => 389.99,
            'minQty' => 3
        ]);
        CustomerPricingRule::unguard();
        foreach ([
            $classicPriceRule,
            $standoutPriceRule,
            $premiumPriceRule
        ] as $priceRule) {
            $customerPricingRule = factory(CustomerPricingRule::class)->create([
                'customer_id' => $customer->getKey(),
                'pricing_rule_id' => PricingRule::whereProviderAlias($priceRule->getAlias())
                ->first()
                ->getKey(),
                'pricing_rule_settings' => json_encode($priceRule->toArray())
            ]);
            $customer->pricingRules()->save($customerPricingRule);
        }
        $checkout = factory(Checkout::class)->create();

        $itemCollection = CheckoutItemCollectionFactory::createForCustomer($customer);

        $expectedPrice = 0;
        //Classic ads are 5 for 4 (Buy 4 free 1), so for 11 items there should be 2 free items (paying for 9)
        $this->addCheckoutItemToCollection(AdType::TYPE_CLASSIC, $itemCollection, $checkout, 11);
        $expectedPrice += AdType::find(AdType::TYPE_CLASSIC)->price * 9;
        //Standout ads are always 309.99
        $this->addCheckoutItemToCollection(AdType::TYPE_STANDOUT, $itemCollection, $checkout, 4);
        $expectedPrice += 309.99 * 4;
        //Premium ads are 389.99 but only if 3 or more.
        $this->addCheckoutItemToCollection(AdType::TYPE_PREMIUM, $itemCollection, $checkout, 2); //doesn't hit min qty
        $expectedPrice += AdType::find(AdType::TYPE_PREMIUM)->price * 2;

        $resolvedPrice = $itemCollection->resolve();
        $this->assertEquals(round($resolvedPrice->price, 2), round($expectedPrice, 2));

        //add premium items above the min qty
        //remove the price for the existing 2 first
        $expectedPrice -= AdType::find(AdType::TYPE_PREMIUM)->price * 2;
        $this->addCheckoutItemToCollection(AdType::TYPE_PREMIUM, $itemCollection, $checkout, 3); //doesn't hit min qty
        $expectedPrice += 389.99 * 5;
        $resolvedPrice = $itemCollection->resolve();
        $this->assertEquals(round($resolvedPrice->price, 2), round($expectedPrice, 2));
    }

    /**
     * Helper to generate checkout items
     * @param integer $adTypeId
     * @param CheckoutItemCollection $itemCollection
     * @param Checkout $checkout
     * @param integer $qty
     * @return CheckoutItemCollection
     */
    protected function addCheckoutItemToCollection (int $adTypeId, CheckoutItemCollection $itemCollection, Checkout $checkout, int $qty = 1) : CheckoutItemCollection
    {
        factory(CheckoutItem::class, $qty)
        ->make()
        ->each(function (CheckoutItem $item) use ($itemCollection, $checkout, $adTypeId) {
            $item->checkout()->associate($checkout);
            $item->adType()->associate(AdType::find($adTypeId));
            $itemCollection->addItem($item);
        });
        return $itemCollection;
    }
}
