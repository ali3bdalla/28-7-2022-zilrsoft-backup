<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateExpensesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('expenses',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer("organization_id");
				$table->integer('chart_id')->default(0);
				$table->integer("creator_id");
				
				
				$table->string('name');
				$table->string('ar_name');
				$table->boolean('appear_in_purchase')->default(true);
				$table->boolean('appear_in_sale')->default(true);
				
				$table->softDeletes();
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
			Schema::dropIfExists('expenses');
		}
	}
