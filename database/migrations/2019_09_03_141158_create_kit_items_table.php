<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKitItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kit_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('kit_id');
            $table->integer('item_id');
            $table->integer('creator_id');
            $table->integer('organization_id');
            $table->float('price',20, 8);
            $table->float('discount',20, 8);
            $table->float('net',20, 8);
            $table->float('total',20, 8);
            $table->float('tax',20, 8);
            $table->float('subtotal',20, 8);
            $table->integer('qty');
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
        Schema::dropIfExists('kit_items');
    }
}
