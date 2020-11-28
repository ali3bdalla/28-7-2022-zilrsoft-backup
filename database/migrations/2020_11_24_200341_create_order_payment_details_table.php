<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateOrderPaymentDetailsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'order_payment_details', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('order_id');
				$table->integer('user_id');
				$table->integer('sender_account_id');
				$table->integer('received_bank_id');
				$table->string('first_name')->nullable();
				$table->string('last_name')->nullable();
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
			Schema::dropIfExists('order_payment_details');
		}
	}
