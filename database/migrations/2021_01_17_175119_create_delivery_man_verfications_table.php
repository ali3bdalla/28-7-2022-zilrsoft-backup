<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryManVerficationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_man_verfications', function (Blueprint $table) {
            $table->bigIncrements('id');
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
        Schema::dropIfExists('delivery_man_verfications');
    }
}


// alter type invoice_items_invoice_type ADD VALUE  IF NOT EXISTS 'inventory_adjustment'
// alter type purchases_invoice_type ADD VALUE  IF NOT EXISTS 'inventory_adjustment'
//alter type invoices_invoice_type ADD VALUE IF NOT EXISTS 'inventory_adjustment'