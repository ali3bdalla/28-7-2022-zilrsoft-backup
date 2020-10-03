<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->integer('vendor_id');
            $table->integer('receiver_id');
            $table->integer('invoice_id');
            $table->string('prefix',30)->nullable();
            $table->integer("parent_id")->default(0);
            $table->string("vendor_invoice_id")->nullable();
            $table->boolean("is_draft")->default(false);
            // ,'pending_purchase'
            $table->enum("invoice_type",['purchase','return_purchase','beginning_inventory'])->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('purchases');
    }
}
