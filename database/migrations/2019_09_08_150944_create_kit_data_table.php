<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateKitDataTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('kit_data',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('kit_id');
				$table->float('total',20,2);
				$table->float('tax',20,2);
				$table->float('net',20,2);
				$table->float('subtotal',20,2);
				$table->float('discount',20,2)->default(0);
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
			Schema::dropIfExists('kit_data');
		}
	}
