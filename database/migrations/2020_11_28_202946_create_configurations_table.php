<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateConfigurationsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'configurations', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('organization_id');
				$table->boolean('switch_online_store')->default(true);
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
			Schema::dropIfExists('configurations');
		}
	}
