<?php
namespace App\Services\PricingRule\Rules;

use Validator;
use App\Models\CheckoutItem;
use App\Services\PricingRule\PricingRuleInterface;

class FixedAdTypePriceRuleWithMinQty extends FixedAdTypeRule implements PricingRuleInterface
{
    protected $fixedPrice;
    protected $minQty;

    public function __construct()
    {
    }

    public function apply(array $checkoutItems): array
    {
        return collect($checkoutItems)->map(function (CheckoutItem $checkoutItem): CheckoutItem {
            if ($this->checkoutItemIsOfAdType($checkoutItem, $this->adTypeId)) {
                if ($this->shouldApplyRule()) {
                    $checkoutItem->appliedPrice = $this->fixedPrice;
                    $checkoutItem->rulesApplied[] = $this->toArray();
                }
            }
            return $checkoutItem;
        })->all();
    }

    protected function shouldApplyRule(array $checkoutItems): bool
    {
        $numOfEligibleAdTypes = collect($checkoutItems)->filter(function (CheckoutItem $checkoutItem) {
            return $this->checkoutItemIsOfAdType($checkoutItem, $this->adTypeId);
        })->count();
        return $numOfEligibleAdTypes >= $this->minQty;
    }

    public function getValidator(array $data): Validator
    {
        return Validator::make($data, [
            'ad_type_id' => 'required|exists:ad_type,id',
            'price' => 'required|integer',
            'min_qty' => 'required|integer|min:1'
        ]);
    }
}
