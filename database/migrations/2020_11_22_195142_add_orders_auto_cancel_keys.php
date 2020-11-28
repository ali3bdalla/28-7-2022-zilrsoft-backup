<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class AddOrdersAutoCancelKeys extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::table(
				'orders', function(Blueprint $table) {
				$table->timestamp('auto_cancel_at')->nullable();
				$table->boolean('is_should_pay_notified')->default(true);
				$table->timestamp('should_pay_last_notification_at')->nullable();
				$table->string('order_secret_code')->nullable();
				$table->string('delivery_man_code')->nullable();
				$table->string('tracking_number')->nullable();
				$table->float('shipping_amount', 20, 8)->nullable();
				$table->integer('managed_by_id')->nullable();
				$table->integer('payment_id')->nullable();
				$table->string('shippable_type')->nullable();
				$table->integer('shippable_id')->nullable();
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
			//
		}
	}
