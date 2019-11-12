<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreatePaymentsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('payments',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('organization_id');
				$table->integer('chart_id')->default(0);
				$table->integer('creator_id');
				$table->integer('user_id');
				
				$table->integer('organization_account_id')->nullable();
				$table->integer('user_account_id')->nullable();
				$table->integer('gateway_id');
				$table->integer('bank_id')->nullable();
				
				$table->boolean('is_belongs_to_invoice')->default(true);
				$table->boolean('is_created_from_invoice')->default(true);
				$table->float("amount",20,8)->default(0);
				$table->string('amount_ar_words')->nullable();
				$table->string('amount_en_words')->nullable();
				
				$table->string('account')->nullable();
				$table->string('account_name')->nullable();
				$table->string('pdf')->nullable();
				$table->string('description')->nullable();
				$table->enum("payment_type",['receipt',"payment"]);
				
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
			Schema::dropIfExists('payments');
		}
	}
