<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateUserGatewaysTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('user_gateways',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('organization_id');
				$table->integer('user_id');
				$table->integer('account_id');
				$table->string('account_name');
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
			Schema::dropIfExists('user_gateways');
		}
	}
