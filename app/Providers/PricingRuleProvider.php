<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\PricingRule\Rules\BuyXFreeYRule;
use App\Services\PricingRule\Rules\FixedAdTypePriceRule;
use App\Services\PricingRule\Rules\FixedAdTypePriceWithMinQtyRule;

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
