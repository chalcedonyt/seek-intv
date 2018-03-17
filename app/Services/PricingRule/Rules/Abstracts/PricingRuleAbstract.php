<?php
namespace App\Services\PricingRule\Rules\Abstracts;

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

}