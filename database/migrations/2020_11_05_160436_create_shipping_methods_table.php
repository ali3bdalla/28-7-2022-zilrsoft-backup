<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateShippingMethodsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'shipping_methods', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->string('name')->nullable();
				$table->string('ar_name')->nullable();
				$table->string('logo')->nullable();
				$table->integer('item_id')->nullable();
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
			Schema::dropIfExists('shipping_methods');
		}
	}
