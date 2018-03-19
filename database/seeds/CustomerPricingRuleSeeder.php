<?php

use Illuminate\Database\Seeder;

use App\Models\AdType;
use App\Models\Customer;
use App\Models\PricingRule;
use App\Models\CustomerPricingRule;

class CustomerPricingRuleSeeder extends Seeder
{
    /**
     * Seeds the database with requirements as per the interview task
     *
     * @return void
     */
    public function run()
    {
        Customer::unguard();
        CustomerPricingRule::unguard();

        $pricingRules = app('PricingRules');
        $pricingRuleFactories = app('PricingRuleFactories');

        // Unilever
        // Gets a 3 for 2 deals on Classic Ads
        // This equates to a buy 2 free 1 setting.
        $unilever = Customer::create([
            'name' => 'Unilever'
        ]);
        $rule = $pricingRuleFactories['buy_x_free_y']::fromArray([
            'adTypeId' => AdType::TYPE_CLASSIC,
            'thresholdQty' => 2
        ]);

        CustomerPricingRule::create([
            'customer_id' => $unilever->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($rule->getAlias())->first()->getKey(),
            'display_name' => 'Unilever - 3 for 2 on Classic Ads',
            'pricing_rule_settings' => json_encode($rule->toArray())
        ]);

        // Apple
        // Gets a discount on Standout Ads where the price drops to $299.99 per ad
        $apple = Customer::create([
            'name' => 'Apple'
        ]);
        $rule = $pricingRuleFactories['fixed_for_ad_type']::fromArray([
            'adTypeId' => AdType::TYPE_STANDOUT,
            'fixedPrice' => 299.99
        ]);

        CustomerPricingRule::create([
            'customer_id' => $apple->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($rule->getAlias())->first()->getKey(),
            'display_name' => 'Apple - Standout ads for $299.99',
            'pricing_rule_settings' => json_encode($rule->toArray())
        ]);

        // Nike
        // Gets a discount on Premium Ads where 4 or more are purchased. The price drops to 379.99 per ad
        $nike = Customer::create([
            'name' => 'Nike'
        ]);
        $rule = $pricingRuleFactories['fixed_for_ad_type_with_min_qty']::fromArray([
            'adTypeId' => AdType::TYPE_PREMIUM,
            'fixedPrice' => 379.99,
            'minQty' => 4
        ]);

        CustomerPricingRule::create([
            'customer_id' => $nike->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($rule->getAlias())->first()->getKey(),
            'display_name' => 'Nike - Premium ads for $379.99 with purchase of 4 or more',
            'pricing_rule_settings' => json_encode($rule->toArray())
        ]);

        // Ford
        // - Gets a 5 for 4 deal on Classic Ads (equates to buy 4 free 1)
        // - Gets a discount on Standout Ads where the price drops to $309.99 per ad
        // - Gets a discount on Premium Ads when 3 or more are purchased. The price drops to $389.99 per ad
        $ford = Customer::create([
            'name' => 'Ford'
        ]);

        $classicRule = $pricingRuleFactories['buy_x_free_y']::fromArray([
            'adTypeId' => AdType::TYPE_CLASSIC,
            'thresholdQty' => 4
        ]);
        CustomerPricingRule::create([
            'customer_id' => $ford->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($classicRule->getAlias())->first()->getKey(),
            'display_name' => 'Ford - 5 for 4 deal on Classic ads',
            'pricing_rule_settings' => json_encode($classicRule->toArray())
        ]);

        $standoutRule = $pricingRuleFactories['fixed_for_ad_type']::fromArray([
            'adTypeId' => AdType::TYPE_STANDOUT,
            'fixedPrice' => 309.99
        ]);
        CustomerPricingRule::create([
            'customer_id' => $ford->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($standoutRule->getAlias())->first()->getKey(),
            'display_name' => 'Ford - $309.99 for Standout ads',
            'pricing_rule_settings' => json_encode($standoutRule->toArray())
        ]);

        $premiumRule = $pricingRuleFactories['fixed_for_ad_type_with_min_qty']::fromArray([
            'adTypeId' => AdType::TYPE_PREMIUM,
            'fixedPrice' => 389.99,
            'minQty' => 3
        ]);
        CustomerPricingRule::create([
            'customer_id' => $ford->getKey(),
            'pricing_rule_id' => PricingRule::whereProviderAlias($premiumRule->getAlias())->first()->getKey(),
            'display_name' => 'Ford - $389.99 for Premium ads with purchase of 3 or more',
            'pricing_rule_settings' => json_encode($premiumRule->toArray())
        ]);
    }
}
