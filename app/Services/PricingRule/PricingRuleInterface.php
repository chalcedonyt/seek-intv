<?php
namespace App\Services\PricingRule;

use App\TransferObjects\ResolvedPrice;
use Validator;

interface PricingRuleInterface
{
    public function apply(array $checkoutItems): array;
    public function getValidator(array $data): Validator;
}