\<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateShippingAddressesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'shipping_addresses', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('user_id');
				$table->integer('country_id')->nullable();
				$table->string('city')->nullable();
				$table->string('state')->nullable();
				$table->string('phone_number')->nullable();
				$table->string('building_number')->nullable();
				$table->string('first_name')->nullable();
				$table->string('last_name')->nullable();
				$table->string('street_name')->nullable();
				$table->text('description')->nullable();
				$table->string('zip_code')->nullable();
				$table->float('lat')->nullable();
				$table->float('lng')->nullable();
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
			Schema::dropIfExists('shipping_addresses');
		}
	}
