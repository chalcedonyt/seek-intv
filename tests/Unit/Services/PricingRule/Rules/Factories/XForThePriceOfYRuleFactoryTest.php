<?php

namespace Tests\Unit\Services\PricingRule\Rules\Factories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\AdType;
use App\Services\PricingRule\Rules\XForThePriceOfYRule;
use App\Services\PricingRule\Rules\Factories\XForThePriceOfYRuleFactory;

class XForThePriceOfYRuleFactoryTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_replicates_serialized_rule()
    {
        $adType = AdType::inRandomOrder()->first();

        $rule = new XForThePriceOfYRule;
        $rule->setAdType($adType);
        $rule->setThresholdQty(rand(5, 10));
        $rule->setCalculatedQty(rand(3, 4));

        $data = $rule->toArray();

        $duplicateRule = XForThePriceOfYRuleFactory::fromArray($data);
        $this->assertEquals(array_values($duplicateRule->toArray()), array_values($data));
        $this->assertEquals(array_keys($duplicateRule->toArray()), array_keys($data));
    }
}
