<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
                'free_x_for_y' => \App\Services\PricingRule\FreeXForEveryYRule::class,
                'fixed_for_ad_type' => \App\Services\PricingRule\FixedAdTypePriceRule::class,
                'fixed_for_ad_type_with_min_qty' => \App\Services\PricingRule\FixedAdTypePriceWithMinQtyRule::class
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
