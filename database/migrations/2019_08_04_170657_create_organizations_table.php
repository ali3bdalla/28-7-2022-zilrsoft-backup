<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	
	// القصيم - الرس - طريق الملك فهد - غرب الاحوال المدنية
	class CreateOrganizationsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('organizations',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->string('title');
				$table->string('title_ar');
				$table->string('city');
				$table->string('city_ar');
				$table->text('description')->nullable();
				$table->text('description_ar')->nullable();
				$table->enum('type',["individual","government","corporation",'establishment'])->default('individual');
				$table->integer('country_id');
				$table->integer('type_id');
				$table->integer('supervisor_id')->nullable();
				$table->string('logo')->nullable();
				$table->string('address')->nullable();
				$table->string('address_ar')->nullable();
				$table->string('phone_number')->nullable();
				$table->string('stamp')->nullable();
				$table->string('vat');
				$table->string('cr');
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
			Schema::dropIfExists('organizations');
		}
	}
