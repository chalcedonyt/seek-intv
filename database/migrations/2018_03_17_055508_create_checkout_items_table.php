<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckoutItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkout_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ad_type_id');
            $table->integer('checkout_id');
            $table->float('applied_price', 8, 2)->nullable()->default(null);
            $table->json('applied_rules')->nullable();
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
        Schema::dropIfExists('checkout_items');
    }
}
