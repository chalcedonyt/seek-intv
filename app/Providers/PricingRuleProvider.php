<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\PricingRule\Rules\BuyXFreeYRule;
use App\Services\PricingRule\Rules\FixedAdTypePriceRule;
use App\Services\PricingRule\Rules\FixedAdTypePriceWithMinQtyRule;

use App\Services\PricingRule\Rules\Factories\BuyXFreeYRuleFactory;
use App\Services\PricingRule\Rules\Factories\FixedAdTypePriceRuleFactory;
use App\Services\PricingRule\Rules\Factories\FixedAdTypePriceWithMinQtyRuleFactory;

class PricingRuleProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('PricingRules', function($app) {
            return [
                (new BuyXFreeYRule)->getAlias() => BuyXFreeYRule::class,
                (new FixedAdTypePriceRule)->getAlias() => FixedAdTypePriceRule::class,
                (new FixedAdTypePriceWithMinQtyRule)->getAlias() => FixedAdTypePriceWithMinQtyRule::class
            ];
         });
         $this->app->singleton('PricingRuleFactories', function($app) {
            return [
                (new BuyXFreeYRule)->getAlias() => BuyXFreeYRuleFactory::class,
                (new FixedAdTypePriceRule)->getAlias() => FixedAdTypePriceRuleFactory::class,
                (new FixedAdTypePriceWithMinQtyRule)->getAlias() => FixedAdTypePriceWithMinQtyRuleFactory::class
            ];
         });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
