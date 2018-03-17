<?php
namespace App\Services\PricingRule\Rules;

use Validator;
use App\Services\PricingRule\PricingRuleInterface;

class FixedAdTypePriceRule extends AdTypePricingRuleAbstract implements PricingRuleInterface
{
    /**
     * @var float
     */
    protected $fixedPrice;

    /**
     * @param float $price
     * @return void
     */
    public function setFixedPrice(float $price)
    {
        $this->fixedPrice = $price;
    }

    public function apply(array $checkoutItems): array
    {
        return collect($checkoutItems)->map(function (CheckoutItem $checkoutItem): CheckoutItem {
            if ($this->checkoutItemIsOfAdType($checkoutItem, $this->adTypeId)) {
                $checkoutItem->appliedPrice = $this->fixedPrice;
                $checkoutItem->rulesApplied[] = $this->toArray();
            }
            return $checkoutItem;
        })->all();
    }

    public function getValidator(array $data): Validator
    {
        return Validator::make($data, [
            'ad_type_id' => 'required|exists:ad_type,id',
            'price' => 'required|integer'
        ]);
    }
}
