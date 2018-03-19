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
    public function getAlias(): string;
    public function getDisplayName(): string;
    public function apply(array $checkoutItems): array;
    public function shouldApply(array $checkoutItems): bool;
    public function getValidator(array $data): Validator;
}