<?php
namespace App\Services\PricingRule\Rules;

use Validator;
use App\Models\CheckoutItem;
use App\Services\PricingRule\PricingRuleInterface;
use App\Services\PricingRule\Rules\Abstracts\AdTypePricingRuleAbstract;

class FixedAdTypePriceRule extends AdTypePricingRuleAbstract implements PricingRuleInterface
{
    /**
     * @var string
     */
    protected $alias = 'fixed_for_ad_type';
    /**
     * @var string
     */
    protected $displayName = 'Fixed price for specific ad type';
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

    /**
     * @param array<CheckoutItem> $checkoutItems
     * @return array<CheckoutItem>
     */
    public function apply(array $checkoutItems): array
    {
        return collect($checkoutItems)->map(function (CheckoutItem $checkoutItem): CheckoutItem {
            if ($this->checkoutItemIsOfAdType($checkoutItem, $this->adType->getKey())) {
                $checkoutItem->applied_price = $this->fixedPrice;
                // $checkoutItem->applied_rules[] = $this->toArray();
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
            'fixedPrice' => $this->fixedPrice
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
            'fixedPrice' => 'required|integer'
        ]);
    }
}
