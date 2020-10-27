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
				$table->string('username');
				$table->integer('otp')->nullable();
				$table->boolean('is_verified')->default(false);
				$table->timestamps();
				
			}
			);
			
			Schema::table(
				'users', function(Blueprint $table) {
				$table->integer('otp_code')->nullable();
				$table->timestamp('verified_at')->nullable();
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
