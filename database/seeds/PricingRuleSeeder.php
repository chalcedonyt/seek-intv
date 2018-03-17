<?php

use Illuminate\Database\Seeder;

use App\Models\PricingRule;

class PricingRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PricingRule::unguard();

        //mapped to App\Providers\PricingRuleProvider
        foreach (app('PricingRules') as $alias => $class) {
            PricingRule::create([
                'display_name' => (new $class)->getDisplayName(),
                'provider_alias' => $alias
            ]);
        }
    }
}
