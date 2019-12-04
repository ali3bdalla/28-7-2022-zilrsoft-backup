<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateAccountsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('accounts',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('organization_id');
				$table->integer('creator_id');
				$table->string('name')->nullable();
				$table->string('ar_name')->nullable();
				$table->integer('parent_id')->default(0);
				$table->string('serial')->nullable();
				$table->boolean('is_gateway')->default(false);
				$table->enum('type',['credit','debit'])->default('credit');
				$table->boolean('is_system_account')->default(false);
				$table->enum('slug',
					[
						'withdrawals',
						'clients',
						'vendors',
						'gateway',
						'items',
						'sales',
						'purchase',
						'assets',
						'current_assets',
						'stock',
						'liabilities',
						'current_liabilities',
						'cogs',
						'expenses',
						'fixed_assets',
						'products_sales_discount',
						'services_sales_discount',
						'other_services_sales_discount',
						'services_return_sales',
						'services_sales',
						'products_return_sales',
						'products_sales',
						'net_sales',
						'income',
						'vat',
						'equity',
						'capital',
						'other_services_return_sales',
						'other_services_sales'
					])->nullable();
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
			Schema::dropIfExists('accounts');
		}
	}
