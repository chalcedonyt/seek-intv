<?php

namespace Tests\Unit\Services\PricingRule\Rules\Factories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\AdType;
use App\Services\PricingRule\Rules\FixedAdTypePriceWithMinQtyRule;
use App\Services\PricingRule\Rules\Factories\FixedAdTypePriceWithMinQtyRuleFactory;

class FixedAdTypePriceWithMinQtyRuleFactoryTest extends TestCase
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

        $rule = new FixedAdTypePriceWithMinQtyRule;
        $rule->setAdType($adType);
        $rule->setMinQty(rand(3, 10));
        $rule->setFixedPrice(rand(2000, 4000));

        $data = $rule->toArray();

        $duplicateRule = FixedAdTypePriceWithMinQtyRuleFactory::fromArray($data);
        $this->assertEquals(array_values($duplicateRule->toArray()), array_values($data));
        $this->assertEquals(array_keys($duplicateRule->toArray()), array_keys($data));
    }
}
