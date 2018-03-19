<?php
namespace App\Services\PricingRule\Rules\Abstracts;

use App\Services\PricingRule\PricingRuleInterface;

abstract class PricingRuleAbstract
{
    /**
     * @var string
     */
    protected $alias;
    /**
     * @var string
     */
    protected $displayName;

    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * Whether this rule should be applied
     *
     * @param array $checkoutItems
     * @return boolean
     */
    abstract public function shouldApply(array $checkoutItems): bool;

    /**
     * Description of this rule
     * @return string
     */
    abstract public function __toString(): string;
}