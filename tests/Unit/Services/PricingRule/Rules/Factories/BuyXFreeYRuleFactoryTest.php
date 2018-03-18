<?php

namespace Tests\Unit\Services\PricingRule\Rules\Factories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\AdType;
use App\Services\PricingRule\Rules\BuyXFreeYRule;
use App\Services\PricingRule\Rules\Factories\BuyXFreeYRuleFactory;

class BuyXFreeYRuleFactoryTest extends TestCase
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

        $rule = new BuyXFreeYRule;
        $rule->setAdType($adType);
        $rule->setThresholdQty(rand(5, 10));
        $rule->setBonusQty(rand(2, 4));

        $data = $rule->toArray();

        $duplicateRule = BuyXFreeYRuleFactory::fromArray($data);
        $this->assertEquals(array_values($duplicateRule->toArray()), array_values($data));
        $this->assertEquals(array_keys($duplicateRule->toArray()), array_keys($data));
    }
}
