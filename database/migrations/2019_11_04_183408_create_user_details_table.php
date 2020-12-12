<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateUserDetailsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create(
				'user_details', function(Blueprint $table) {
				$table->bigIncrements('id');
				$table->integer('user_id');
				$table->string('email_address')->nullable();
				$table->string('address')->nullable();
				$table->string('cr')->nullable();
				$table->string('vat')->nullable();
				$table->string('responsible_name')->nullable();
				$table->string('responsible_phone_number')->nullable();
				$table->timestamps();
			}
			);
		}
		
		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			Schema::dropIfExists('user_details');
		}
	}
