<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class ItemsOnlineAttributes extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::table(
				'items', function(Blueprint $table) {
				$table->float('weight')->nullable();
				$table->float('online_offer_price', 20, 8)->nullable();
				$table->float('shipping_discount')->default(0);
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
