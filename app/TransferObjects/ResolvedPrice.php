<?php
namespace App\TransferObjects;

class ResolvedPrice extends TransferObjectAbstract
{
    protected $price;
    protected $appliedPricingRules;

    public function __construct(float $price, array $appliedPricingRules)
    {
        $this->price = $price;
        $this->appliedPricingRules = $appliedPricingRules;
    }
}