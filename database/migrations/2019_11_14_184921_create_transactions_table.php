<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateTransactionsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('transactions',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('creator_id');
				$table->integer('organization_id');
				$table->integer('account_id');
				$table->enum('type',['credit','debit'])->default('debit');
				$table->integer('user_id')->nullable();
				$table->integer('item_id')->nullable();
				// $table->string('debitable_id')->nullable();
				// $table->string('debitable_type')->nullable();
				// $table->integer('creditable_id')->nullable();
				// $table->string('creditable_type')->nullable();
				$table->float('amount',20,2);
				$table->boolean("is_manual")->default(false);
				$table->boolean("is_pending")->default(false);
				$table->integer('invoice_id')->nullable();
				$table->integer('container_id')->nullable();
				$table->string('description')->nullable();


				// ,[
				// 	'to_stock',
				// 	'to_item',
				// 	'to_gateway',
				// 	'to_tax',
				// 	'to_cogs',
				// 	'to_products_sales',
				// 	'to_services_sales',
				// 	'to_other_services_sales',
				// 	'to_products_sales_discount',
				// 	'to_services_sales_discount',
				// 	'to_other_services_sales_discount',
				// 	'client_balance',
				// 	'vendor_balance',
				// 	'close_account',
				
				// ]


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
			Schema::dropIfExists('transactions');
		}
	}
