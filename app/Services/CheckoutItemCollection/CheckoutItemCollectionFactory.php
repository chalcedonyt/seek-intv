<?php
namespace App\Services\CheckoutItemCollection;

use App\Models\CheckoutItem;
use App\Models\Customer;
use App\Models\CustomerPricingRule;
use App\Services\CheckoutItemCollection\CheckoutItemCollection;

class CheckoutItemCollectionFactory
{
    public static function createForCustomer(Customer $customer): CheckoutItemCollection
    {
        $collection = new CheckoutItemCollection();
        $collection->setCustomer($customer);
        //get the applicable rules for the customer
        $customer->pricingRules->each( function (CustomerPricingRule $cpr) use ($collection) {
            $factoryClass = app('PricingRuleFactories')[$cpr->pricingRule->provider_alias];
            $rule = $factoryClass::fromArray(json_decode($cpr->pricing_rule_settings, true));
            $collection->addRule($rule);
        });
        return $collection;
    }
}