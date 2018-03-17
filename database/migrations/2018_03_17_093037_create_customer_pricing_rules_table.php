<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerPricingRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_pricing_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('pricing_rule_id');
            $table->string('display_name');
            $table->json('pricing_rule_settings')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_pricing_rules');
    }
}
