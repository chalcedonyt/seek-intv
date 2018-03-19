<?php
namespace App\Services\CheckoutItemCollection;

use App\Models\CheckoutItem;
use App\Models\Customer;
use App\TransferObjects\ResolvedPrice;
use App\Services\PricingRule\PricingRuleInterface;

class CheckoutItemCollection
{
    /**
     * @var array<CheckoutItem>
     */
    protected $items = [];
    /**
     * @var array<PricingRuleInterface>
     */
    protected $rules = [];

    protected $customer;

    public function setCustomer(Customer $c)
    {
        $this->customer = $c;
    }

    /**
     * @param CheckoutItem $item
     * @return CheckoutItemCollection
     */
    public function addItem(CheckoutItem $item)
    {
        $this->items[]= $item;
        return $this;
    }

    /**
     * @param array<CheckoutItem> $items
     * @return CheckoutItemCollection
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param PricingRuleInterface $rule
     * @return void
     */
    public function addRule(PricingRuleInterface $rule)
    {
        $this->rules[]= $rule;
        return $this;
    }

    /**
     * @return ResolvedPrice
     */
    public function resolve(): ResolvedPrice
    {
        $resolvedItems = collect($this->rules)->reduce(function (array $carriedItems, PricingRuleInterface $rule) {
            return $rule->apply($carriedItems);
        }, $this->items);

        $price = $this->resolveItemPrices($resolvedItems);
        $appliedRules = $this->getAppliedRules($resolvedItems);
        return new ResolvedPrice($price, $appliedRules);
    }

    /**
     * @param array $resolvedItems
     * @return float
     */
    protected function resolveItemPrices(array $resolvedItems): float
    {
        $price = collect($resolvedItems)->reduce(function (float $carriedPrice, CheckoutItem $item) {
            //important to differentiate NULL from 0 here.
            if (is_null($item->applied_price)) {
                $item->applied_price = $item->adType->price;
            }
            $carriedPrice+=$item->applied_price;
            return $carriedPrice;
        }, 0);
        return round($price, 2);
    }

    /**
     *
     * @param array $checkoutItems
     * @return array
     */
    protected function getAppliedRules(array $checkoutItems): array
    {
        return collect($this->rules)->reduce(function (array $appliedPricingRules, PricingRuleInterface $rule) use ($checkoutItems) {
            if ($rule->shouldApply($checkoutItems)) {
                //calls the required __toString method
                $appliedPricingRules[]= (string)$rule;
            }
            return $appliedPricingRules;
        }, []);
    }
}