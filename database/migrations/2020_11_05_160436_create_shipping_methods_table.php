<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'shipping_methods',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->string('ar_name')->nullable();
                $table->string('logo')->nullable();
                $table->float('max_base_weight')->default(15);
                $table->boolean('is_required_shipping_address')->default(true);

                $table->float('max_base_weight_cost')->default(30);
                $table->float('kg_after_max_weight_cost')->default(2);

                $table->float('max_base_weight_price')->default(30);
                $table->float('kg_rate_after_max_price')->default(2);

                $table->integer('item_id')->nullable();
                $table->softDeletes();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_methods');
    }
}
