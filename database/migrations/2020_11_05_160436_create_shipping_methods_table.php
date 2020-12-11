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
            'shipping_methods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('ar_name')->nullable();
            $table->string('logo')->nullable();
            $table->float('max_base_weight')->default(15);

            $table->float('max_base_weight_cost')->default(30);
            $table->float('kg_after_max_weight_cost')->default(2);

            $table->float('max_base_weight_price')->default(30);
            $table->float('kg_rate_after_max_price')->default(2);


            $table->integer('item_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

//
//
//                 DROP TABLE zilrsoft_production.delivery_men;
//                 DROP TABLE zilrsoft_production.orders;
//                 DROP TABLE zilrsoft_production.shipping_methods;
//                 DROP TABLE zilrsoft_production.shipping_addresses;
//            DELETE from public.migrations WHERE migration = '2020_11_28_204551_create_delivery_men_table';
//DELETE from public.migrations WHERE migration = '2020_11_02_161625_create_orders_table';
//DELETE from public.migrations WHERE migration = '2020_11_05_160436_create_shipping_methods_table';
//DELETE from public.migrations WHERE migration = '2020_11_02_161223_create_shipping_addresses_table';
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
