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
                }
            }
            return $checkoutItem;
        })->all();
    }

    /**
     * @return boolean
     */
    public function shouldApply(array $checkoutItems): bool
    {
        return count($this->itemsOfAdType($checkoutItems)) >= $this->minQty;
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
     * @return \Illuminate\Validation\Validator
     */
    public function getValidation(array $data): \Illuminate\Validation\Validator
    {
        return Validator::make($data, [
            'adTypeId' => 'required|exists:ad_types,id',
            'fixedPrice' => 'required|numeric|max:'.$this->adType->price,
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

    /**
     * Description of this rule
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s: fixed price of $%.2f for min qty of %d',
            $this->adType->display_name,
            $this->fixedPrice,
            $this->minQty
        );
    }
}
