<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateManagerGatewaysTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('manager_gateways',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('gateway_id');
				$table->integer('manager_id');
				$table->integer('order_number')->nullable();
				$table->integer('organization_id');
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
			Schema::dropIfExists('manager_gateways');
		}
	}
