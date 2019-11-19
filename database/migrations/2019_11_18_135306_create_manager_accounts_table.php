<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateManagerAccountsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('manager_accounts',function (Blueprint $table){
				$table->integer('id');
				$table->integer('manager_id');
				$table->integer('account_id');
				$table->enum('to',[
					'vat',
				
				]);
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
			Schema::dropIfExists('manager_accounts');
		}
	}
