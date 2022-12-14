<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateCitiesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'cities', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('country_id');
				$table->string('name')->nullable();
				$table->string('smsa_id')->nullable();
				$table->boolean('is_active')->default(true);
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
			Schema::dropIfExists('cities');
		}
	}
