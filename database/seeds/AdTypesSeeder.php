<?php

use Illuminate\Database\Seeder;

use App\Models\AdType;

class AdTypesSeeder extends Seeder
{
    /**
     * Seed with the ad types in the intv brief
     *
     * @return void
     */
    public function run()
    {
        AdType::unguard();
        AdType::create([
            'code' => 'classic',
            'display_name' => 'Classic Ad',
            'price' => 269.99
        ]);
        AdType::create([
            'code' => 'standout',
            'display_name' => 'Standout Ad',
            'price' => 322.99
        ]);
        AdType::create([
            'code' => 'premium',
            'display_name' => 'Premium Ad',
            'price' => 394.99
        ]);
    }
}
