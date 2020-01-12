<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateManagerPrivateTransactionsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('manager_private_transactions',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->enum('transaction_type',['close_account','transfer']);
				$table->integer('transaction_container_id');
				$table->dateTime('close_account_start_date')->nullable();
				$table->dateTime('close_account_end_date')->nullable();
				$table->boolean('is_pending')->default(false);
				$table->float('amount',20,8)->default(0);
				$table->integer('creator_id');
				$table->integer('transfer_id');
				$table->float('shortage_amount')->default(0);
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
			Schema::dropIfExists('manager_private_transactions');
		}
	}
