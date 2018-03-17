<?php
namespace App\Services\PricingRule\Rules;

use Validator;
use App\Services\PricingRule\PricingRuleInterface;

class FreeXForEveryYRule extends AdTypePricingRuleAbstract implements PricingRuleInterface
{
    /**
     * The number of free items given for every y items checked out
     *
     * @var int
     */
    protected $x;
    /**
     * The number of items needed in a checkout
     *
     * @var int
     */
    protected $y;

    /**
     * @param integer $x
     * @return void
     */
    public function setX(int $x)
    {
        $this->x = $x;
    }

    /**
     * @param integer $y
     * @return void
     */
    public function setY(int $y)
    {
        $this->y = $y;
    }

    public function apply(array $checkoutItems): array
    {
        $freeItemCount = $this->numFreeItems($checkoutItems);

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
     * Return the number of free items applicable.
     * E.g. For a "Free 2 for every 3", return 4 if the input is 7.
     *
     * @param array $checkoutItems
     * @return integer
     */
    public function numFreeItems(array $checkoutItems): int
    {
        $eligibleItems = $this->itemsOfAdType($checkoutItems);
        $freeItemCount = $this->x * floor(count($eligibleItems)/$this->y);
        return $freeItemCount;
    }

    protected function shouldApplyRule(array $checkoutItems): bool
    {
        return true;
    }

    public function getValidator(array $data): Validator
    {
        //Free 1 for every 3 would be valid, while free 4 for every 3 would not be allowed
        $max_x = isset($data['y']) && $data['y'] > 1
        ? intval($data['y'])-1
        : 1;

        return Validator::make($data, [
            'ad_type_id' => 'required|exists:ad_type,id',
            'x' => 'required|integer|min:1|max:'.$max_x,
            'y' => 'required|integer|min:1'
        ]);
    }
}
