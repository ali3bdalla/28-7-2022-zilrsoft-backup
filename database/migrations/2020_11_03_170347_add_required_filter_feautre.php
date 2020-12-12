<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class AddRequiredFilterFeautre extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			//
			Schema::table(
				'filters', function(Blueprint $table) {
				$table->boolean('is_required_filter')->default(false);
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
