<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('shipping_transactions');
        Schema::create('shipping_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shipping_method_id');
            $table->integer('delivery_man_id')->nullable();
            $table->integer('creator_id');
            $table->integer('city_id');
            $table->string('receiver_type')->nullable();
            $table->integer('receiver_id')->nullable();
            $table->float('cost_amount')->default(0);
            $table->float('sales_amount')->default(0);
            $table->float('weight')->default(0);
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['issued', 'shipped', 'received', 'returned', "canceled"])->default("issued");
            $table->string('tracking_number')->nullable();
            $table->text('extra')->nullable();

            $table->string('reference')->nullable();
            $table->string('address')->nullable();
            $table->string('description')->nullable();
            $table->string('cod')->nullable();
            $table->string('boxes')->nullable();
            $table->integer('order_id')->nullable();
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
