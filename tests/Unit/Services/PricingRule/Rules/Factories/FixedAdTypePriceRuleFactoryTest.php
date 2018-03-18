<?php

namespace Tests\Unit\Services\PricingRule\Rules\Factories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\AdType;
use App\Services\PricingRule\Rules\FixedAdTypePriceRule;
use App\Services\PricingRule\Rules\Factories\FixedAdTypePriceRuleFactory;

class FixedAdTypePriceRuleFactoryTest extends TestCase
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

        $rule = new FixedAdTypePriceRule;
        $rule->setAdType($adType);
        $rule->setFixedPrice(rand(2000, 4000));

        $data = $rule->toArray();

        $duplicateRule = FixedAdTypePriceRuleFactory::fromArray($data);
        $this->assertEquals(array_values($duplicateRule->toArray()), array_values($data));
        $this->assertEquals(array_keys($duplicateRule->toArray()), array_keys($data));
    }
}
