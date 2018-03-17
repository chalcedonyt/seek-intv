<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdTypesSeeder::class);
        $this->call(PricingRuleSeeder::class);
        $this->call(CustomerPricingRuleSeeder::class);
    }
}
