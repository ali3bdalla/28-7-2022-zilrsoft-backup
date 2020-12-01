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
				'orders', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('user_id');
				$table->integer('draft_id');
				$table->integer('invoice_id')->nullable();
				
				$table->float('net', 20, 8)->nullable();
				$table->integer('shipping_address_id')->nullable();
				$table->enum('status', ['issued', 'pending', 'paid', 'in_progress', 'ready_for_shipping', 'shipped', 'delivered', 'canceled', 'returned'])->default('issued');
			
				
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
