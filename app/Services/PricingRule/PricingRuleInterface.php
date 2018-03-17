<?php
namespace App\Services\PricingRule;

use App\TransferObjects\ResolvedPrice;
use Validator;

interface PricingRuleInterface
{
    /**
     * Apply pricing rules to the items in checkout
     *
     * @param array<CheckoutItem> $checkoutItems
     * @return array<CheckoutItem> A new array
     */
    public function apply(array $checkoutItems): array;

    public function getValidator(array $data): Validator;
}