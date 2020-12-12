<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateTranslationsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			if(!Schema::hasTable('translations'))
			{
				Schema::create(
					'translations', function(Blueprint $table) {
					$table->bigIncrements('id');
					$table->morphs('translatable');
					$table->string('key')->nullable();
					$table->string('language')->nullable();
					$table->text('content')->nullable();
					$table->softDeletes();
					$table->timestamps();
				}
				);
			}
			
		}
		
		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			Schema::dropIfExists('translations');
		}
	}
