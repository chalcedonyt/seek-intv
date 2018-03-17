<?php
namespace App\Services\PricingRule\Rules;

use Validator;
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
     * The number of free items given for every y items checked out
     *
     * @var int
     */
    protected $bonusQty;
    /**
     * The number of items needed in a checkout to trigger
     *
     * @var int
     */
    protected $thresholdQty;

    /**
     * @param integer $qty
     * @return void
     */
    public function setBonusQty(int $qty)
    {
        $this->bonusQty = $qty;
    }

    /**
     * @param integer $thresholdQty
     * @return void
     */
    public function setThresholdQty(int $thresholdQty)
    {
        $this->thresholdQty = $thresholdQty;
    }

    public function toArray(): array
    {
        $parent = parent::toArray();
        return array_merge($parent, [
            'bonusQty' => $this->bonusQty,
            'thresholdQty' => $this->thresholdQty
        ]);
    }

    public function apply(array $checkoutItems): array
    {
        $freeItemCount = $this->totalBonusQty($checkoutItems);

        return collect($checkoutItems)->map(function (CheckoutItem $checkoutItem) use (&$freeItemCount): CheckoutItem {
            if ($this->checkoutItemIsOfAdType($checkoutItem, $this->adType->getKey()) && $freeItemCount > 0) {
                $checkoutItem->applied_price = 0;
                // $checkoutItem->rulesApplied[] = $this->toArray();
                $freeItemCount--;
            }
            return $checkoutItem;
        })->all();
    }

    /**
     * Return the number of bonus items applicable.
     * E.g. For a "Buy 3 free 2", return 4 if the input is 7.
     *
     * @param array $checkoutItems
     * @return integer
     */
    public function totalBonusQty(array $checkoutItems): int
    {
        $eligibleItems = $this->itemsOfAdType($checkoutItems);
        $totalBonusQty = $this->bonusQty * floor(count($eligibleItems)/$this->thresholdQty);
        return $totalBonusQty;
    }

    protected function shouldApplyRule(array $checkoutItems): bool
    {
        return true;
    }

    public function getValidator(array $data): Validator
    {
        //Free 1 for every 3 would be valid, while free 4 for every 3 would not be allowed
        $maxBonusQty = isset($data['threshold_qty']) && $data['threshold_qty'] > 1
        ? intval($data['threshold_qty'])-1
        : 1;

        return Validator::make($data, [
            'ad_type_id' => 'required|exists:ad_type,id',
            'bonus_qty' => 'required|integer|min:1|max:'.$maxBonusQty,
            'threshold_qty' => 'required|integer|min:1'
        ]);
    }
}
