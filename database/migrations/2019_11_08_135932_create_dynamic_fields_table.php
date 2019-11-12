<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateDynamicFieldsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('dynamic_fields',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('dynamicable_id');
				$table->string('dynamicable_type');
				$table->enum('field_type',['text','phone','date','datetime','boolean','textarea','list']);
				$table->json('list_options')->nullable();
				$table->boolean('notifiable')->default(false);
				$table->json('notification_content')->nullable();
				$table->timestamps();
			});
		}
		
		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			Schema::dropIfExists('dynamic_fields');
		}
	}
