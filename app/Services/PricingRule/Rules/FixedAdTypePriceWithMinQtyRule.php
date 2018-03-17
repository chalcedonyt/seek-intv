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

    /**
     *
     * @param integer $qty
     * @return void
     */
    public function setMinQty(int $qty)
    {
        $this->minQty = $qty;
    }

    /**
     * @param array<CheckoutItem> $checkoutItems
     * @return array<CheckoutItem>
     */
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

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $parent = parent::toArray();
        return array_merge($parent, [
            'minQty' => $this->minQty
        ]);
    }

    /**
     *
     * @param array $data
     * @return Validator
     */
    public function getValidator(array $data): Validator
    {
        return Validator::make($data, [
            'adTypeId' => 'required|exists:ad_type,id',
            'fixedPrice' => 'required|integer',
            'minQty' => 'required|integer|min:1'
        ]);
    }

    /**
     * @param array<CheckoutItem> $checkoutItems
     * @return boolean
     */
    public function hasMinQty(array $checkoutItems): bool
    {
        $numOfEligibleAdTypes = collect($checkoutItems)->filter(function (CheckoutItem $checkoutItem) {
            return $this->checkoutItemIsOfAdType($checkoutItem, $this->adType->getKey());
        })->count();
        return $numOfEligibleAdTypes >= $this->minQty;
    }
}
