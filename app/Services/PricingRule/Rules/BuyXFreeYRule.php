<?php
namespace App\Services\PricingRule\Rules;

use Validator;
use App\Models\CheckoutItem;

use App\Services\PricingRule\PricingRuleInterface;
use App\Services\PricingRule\Rules\Abstracts\AdTypePricingRuleAbstract;

class BuyXFreeYRule extends AdTypePricingRuleAbstract implements PricingRuleInterface
{
    /**
     * @var string
     */
    protected $alias = 'buy_x_free_y';
    /**
     * @var string
     */
    protected $displayName = 'Buy X free Y';
    /**
     * The number of items needed in a checkout to trigger 1 free item
     *
     * @var int
     */
    protected $thresholdQty;

    /**
     * @param integer $thresholdQty
     * @return void
     */
    public function setThresholdQty(int $thresholdQty)
    {
        $this->thresholdQty = $thresholdQty;
    }

    /**
     *
     * @return array
     */
    public function toArray(): array
    {
        $parent = parent::toArray();
        return array_merge($parent, [
            'thresholdQty' => $this->thresholdQty
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
            'thresholdQty' => 'required|integer|min:1'
        ]);
    }

    /**
     * @param array<CheckoutItem> $checkoutItems
     * @return array<CheckoutItem>
     */
    public function apply(array $checkoutItems): array
    {
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
        return $this->thresholdQty < count($this->itemsOfAdType($checkoutItems));
    }

    /**
     * Return the number of bonus items applicable - bounded by the qty in checkout.
     * E.g. For a "Buy 3 free 1":
     * 7 items in cart would resolve to 1 free item.
     * 8 items in cart would resolve to 2 free items.
     *
     * @param array $checkoutItems
     * @return integer
     */
    public function totalBonusQty(array $checkoutItems): int
    {
        $eligibleItems = $this->itemsOfAdType($checkoutItems);
        $bonusItemCount = 0;
        $thresholdForBonus = $this->thresholdQty;
        for ($i = 1; $i < count($eligibleItems)+1; $i++) {
            if ($i > $thresholdForBonus) {
                $bonusItemCount++;
                $thresholdForBonus += $this->thresholdQty+1;
            }
        }
        return $bonusItemCount;
    }

    /**
     * Description of this rule
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('%s: %d for %d',
            $this->adType->display_name,
            $this->thresholdQty+1,
            $this->thresholdQty
        );
    }
}
