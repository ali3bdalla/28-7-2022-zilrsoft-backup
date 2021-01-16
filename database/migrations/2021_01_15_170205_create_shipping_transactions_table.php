<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        
        Schema::create('shipping_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shipping_method_id');
            $table->integer('delivery_man_id');
            $table->integer('creator_id');
            $table->morphs('receiver');
            $table->float('cost_amount')->default(0);
            $table->float('sales_amount')->default(0);
            $table->float('weight')->default(0);
            $table->string('transaction_id')->nullable();
            $table->enum('status',['issued','shipped','received','returned',"canceled"])->default("issued");
            $table->string('tracking_number')->nullable();
            $table->text('extra')->nullable();
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
        Schema::dropIfExists('shipping_transactions');
    }
}
