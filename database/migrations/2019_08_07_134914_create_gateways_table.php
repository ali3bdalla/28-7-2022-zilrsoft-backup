<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateGatewaysTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('gateways',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('organization_id')->default(0);
				$table->integer('chart_id')->default(0);
				$table->string('name')->nullable();
				$table->string('ar_name')->nullable();
				$table->boolean('is_has_fields')->nullable();
				$table->boolean('is_need_banks')->default(0);
				$table->boolean('is_default')->default(0);
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
			Schema::dropIfExists('gateways');
		}
	}
