<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id');
            $table->integer('draft_id');
            $table->integer('invoice_id')->nullable();

            $table->float('net', 20, 8)->nullable();
            $table->integer('shipping_address_id')->nullable();
            $table->enum('status', ['issued', 'pending', 'paid', 'in_progress', 'ready_for_shipping', 'shipped', 'delivered', 'canceled', 'returned'])->default('issued');


            $table->timestamp('auto_cancel_at')->nullable();
            $table->boolean('is_should_pay_notified')->default(true);
            $table->timestamp('should_pay_last_notification_at')->nullable();
            $table->string('order_secret_code')->nullable();
            $table->string('delivery_man_code')->nullable();
            $table->string('tracking_number')->nullable();
            $table->float('shipping_amount', 20, 8)->nullable();
            $table->integer('managed_by_id')->nullable();
            $table->integer('payment_id')->nullable();
            $table->integer('shipping_method')->nullable();
            $table->integer('delivery_man_id')->nullable();

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
        Schema::dropIfExists('orders');
    }
}
