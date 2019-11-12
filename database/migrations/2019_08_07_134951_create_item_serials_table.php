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


            $table->integer('purchase_invoice_id');
            $table->integer('sale_invoice_id')->default(0);
            $table->integer('r_sale_invoice_id')->default(0);
            $table->integer('r_purchase_invoice_id')->default(0);
            $table->integer('saled_by')->default(0)->nullable();



            $table->string('serial');
            $table->timestamp('sale_at')->nullable();


            $table->enum('current_status',['saled','available','r_sale','r_purchase'])->default("available");

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
