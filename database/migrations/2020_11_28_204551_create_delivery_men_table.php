<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateDeliveryMenTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'delivery_men', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('organization_id');
				$table->integer('creator_id');
				$table->string('first_name')->nullable();
				$table->string('last_name')->nullable();
				$table->string('id_number')->nullable();
				$table->string('phone_number')->nullable();
				$table->integer('city_id')->nullable();
				$table->string('hash')->nullable();
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
			Schema::dropIfExists('delivery_men');
		}
	}
