<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateOrderActivitiesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'order_activities', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('order_id');
				$table->morphs('doable');
				$table->enum('activity', ['issued', 'pending', 'paid', 'in_progress', 'ready_for_shipping', 'shipped', 'delivered', 'canceled', 'returned', 'other'])->default('other');
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
			Schema::dropIfExists('order_activities');
		}
	}
