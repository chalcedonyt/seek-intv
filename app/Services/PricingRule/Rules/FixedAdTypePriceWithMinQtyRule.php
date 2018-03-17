<?php
namespace App\Services\PricingRule\Rules;

use Validator;
use App\Models\CheckoutItem;
use App\Services\PricingRule\PricingRuleInterface;

class FixedAdTypePriceWithMinQtyRule extends FixedAdTypePriceRule implements PricingRuleInterface
{
    /**
     * @var string
     */
    protected $alias = 'fixed_for_ad_type_with_min_qty';
    /**
     * @var string
     */
    protected $displayName = 'Fixed price for specific ad type if minimum qty reached';
    /**
     * @var int
     */
    protected $minQty;

    public function setMinQty(int $qty)
    {
        $this->minQty = $qty;
    }

    public function apply(array $checkoutItems): array
    {
        return collect($checkoutItems)->map(function (CheckoutItem $checkoutItem) use ($checkoutItems): CheckoutItem {
            if ($this->checkoutItemIsOfAdType($checkoutItem, $this->adType->getKey())) {
                if ($this->hasMinQty($checkoutItems)) {
                    $checkoutItem->applied_price = $this->fixedPrice;
                    // $checkoutItem->rulesApplied[] = $this->toArray();
                }
            }
            return $checkoutItem;
        })->all();
    }

    public function toArray(): array
    {
        $parent = parent::toArray();
        return array_merge($parent, [
            'minQty' => $this->minQty
        ]);
    }

    /**
     * @param array $checkoutItems
     * @return boolean
     */
    public function hasMinQty(array $checkoutItems): bool
    {
        $numOfEligibleAdTypes = collect($checkoutItems)->filter(function (CheckoutItem $checkoutItem) {
            return $this->checkoutItemIsOfAdType($checkoutItem, $this->adType->getKey());
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
