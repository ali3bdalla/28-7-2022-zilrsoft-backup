<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateItemQtyHoldsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'order_item_qty_holders', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('item_id');
				$table->timestamp('hold_created_at');
				$table->timestamp('hold_destroy_at');
				$table->integer('order_id');
				$table->integer('qty');
				$table->enum('status',['hold','destroyed','pending'])->default("hold");
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
			Schema::dropIfExists('item_qty_holds');
		}
	}
