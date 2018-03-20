<?php
namespace App\Services\PricingRule\Rules;

use Validator;
use App\Models\CheckoutItem;

use App\Services\PricingRule\PricingRuleInterface;
use App\Services\PricingRule\Rules\Abstracts\AdTypePricingRuleAbstract;

class XForThePriceOfYRule extends AdTypePricingRuleAbstract implements PricingRuleInterface
{
    /**
     * @var string
     */
    protected $alias = 'x_for_the_price_of_y';
    /**
     * @var string
     */
    protected $displayName = 'X for the price of Y';

    /**
     * @var int
     */
    protected $thresholdQty;
    /**
     * @var int
     */
    protected $calculatedQty;

    /**
     * The qty that triggers the price
     * @param integer $thresholdQty
     * @return void
     */
    public function setThresholdQty(int $thresholdQty)
    {
        $this->thresholdQty = $thresholdQty;
    }

    /**
     * The equivalent qty
     * @param integer $calculatedQty
     * @return void
     */
    public function setCalculatedQty(int $calculatedQty)
    {
        $this->calculatedQty = $calculatedQty;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $parent = parent::toArray();
        return array_merge($parent, [
            'thresholdQty' => $this->thresholdQty,
            'calculatedQty' => $this->calculatedQty,
        ]);
    }

    /**
     *
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    public function getValidation(array $data): \Illuminate\Validation\Validator
    {
        $thresholdQty = isset($data['thresholdQty']) ? $data['thresholdQty'] : 0;
        return Validator::make($data, [
            'adTypeId' => 'required|exists:ad_types,id',
            'thresholdQty' => 'required|integer|min:1',
            'calculatedQty' => 'required|integer|max:'.($data['thresholdQty']-1)
        ]);
    }

    /**
     * @param array<CheckoutItem> $checkoutItems
     * @return array<CheckoutItem>
     */
    public function apply(array $checkoutItems): array
    {
        //number of items to effectively exclude from the calculation
        $freeItemCount = $this->totalBonusQty($checkoutItems);

        return collect($checkoutItems)->map(function (CheckoutItem $checkoutItem) use (&$freeItemCount): CheckoutItem {
            if ($this->checkoutItemIsOfAdType($checkoutItem, $this->adType->getKey()) && $freeItemCount > 0) {
                $checkoutItem->applied_price = 0;
                --$freeItemCount;
            }
            return $checkoutItem;
        })->all();
    }

    /**
     * @param array $checkoutItems
     * @return boolean
     */
    public function shouldApply(array $checkoutItems): bool
    {
        return $this->thresholdQty <= count($this->itemsOfAdType($checkoutItems));
    }

    /**
     * Return the number of bonus items applicable - bounded by the qty in checkout.
     * E.g. For a "4 for the price of 3":
     * 7 items in cart would resolve to 1 free item (3 + 1 free item + 3 items)
     * 8 items in cart would resolve to 2 free items (3 + 1 free item + 3 items + 1 free item)
     *
     * @param array $checkoutItems
     * @return integer
     */
    public function totalBonusQty(array $checkoutItems): int
    {
        $eligibleItemQty = count($this->itemsOfAdType($checkoutItems));
        if ($eligibleItemQty < $this->thresholdQty)
            return 0;
        return $eligibleItemQty - (ceil($eligibleItemQty/$this->thresholdQty*$this->calculatedQty));
    }

    /**
     * Description of this rule
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s: %d for the price of %d',
            $this->adType->display_name,
            $this->thresholdQty,
            $this->calculatedQty
        );
    }
}
