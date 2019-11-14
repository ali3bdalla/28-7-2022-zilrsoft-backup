<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateEntriesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('entries',function (Blueprint $table){
				$table->bigIncrements('id');
				
				$table->integer('organization_id');
				$table->integer('chart_id');
				$table->integer('creator_id');
				$table->integer('user_id');
				$table->integer('parent_id');
				$table->string('parent_type');
				$table->float('amount',20,8);
				$table->enum('description',[
					'paid_amount',
					'unpaid_amount'
				])->nullable();
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
			Schema::dropIfExists('entries');
		}
	}
