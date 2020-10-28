<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class OnlineUsers extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			
			Schema::create(
				'online_users_placeholder', function($table) {
				$table->increments('id');
				$table->string('phone_number');
				$table->string('username')->nullable();
				$table->string('password')->nullable();
				$table->string('otp')->nullable();
				$table->timestamps();
				
			}
			);
			
			//
		}
		
		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			//
		}
	}
