<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->integer('vendor_id');
            $table->integer('receiver_id');
            $table->integer('invoice_id');
            $table->boolean("is_full_returned")->default(0);
            $table->boolean("is_returned")->default(0);
            $table->string('prefix',30)->nullable();
            $table->integer("parent_id")->default(0);
            $table->string("vendor_inc_number")->nullable();
            $table->enum("invoice_type",['purchase','r_purchase','beginning_inventory','pend_'])->nullable();
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
        Schema::dropIfExists('purchase_invoices');
    }
}
