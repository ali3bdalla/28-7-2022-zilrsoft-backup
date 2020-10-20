<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateTransactionsContainersTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('transactions_containers',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('creator_id');
				$table->integer('invoice_id')->default(0);
				$table->integer('organization_id');
				$table->float('amount',20, 8);
				$table->string('description')->nullable();
				$table->boolean('is_pending')->default(false);
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
			Schema::dropIfExists('transactions_containers');
		}
	}
