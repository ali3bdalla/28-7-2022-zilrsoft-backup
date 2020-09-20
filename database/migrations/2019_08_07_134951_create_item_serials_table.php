<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemSerialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_serials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('item_id');
            $table->integer('organization_id');
            $table->integer('creator_id');
            $table->integer('purchase_id');
            $table->integer('sale_id')->default(0);
            $table->integer('return_sale_id')->default(0);
            $table->integer('return_purchase_id')->default(0);
            $table->string('serial');
            $table->enum('status',['in_stock','return_sale','return_purchase','sold'])->default("in_stock");
            $table->boolean('is_pending')->default(false);

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
        Schema::dropIfExists('item_serials');
    }
}
