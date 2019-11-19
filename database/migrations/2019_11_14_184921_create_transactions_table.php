<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateTransactionsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('transactions',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('creator_id');
				$table->integer('organization_id');
				$table->string('debitable_id');
				$table->string('debitable_type');
				$table->integer('creditable_id');
				$table->string('creditable_type');
				$table->float('amount',20,8);
				$table->integer('user_id')->nullable();
				$table->integer('invoice_id')->nullable();
				$table->enum('description',[
					'to_stock',
					'to_item',
					'to_gateway',
					'to_tax'
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
			Schema::dropIfExists('transactions');
		}
	}
