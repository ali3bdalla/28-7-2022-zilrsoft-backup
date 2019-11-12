<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class CreateChartsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('charts',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('organization_id');
				$table->integer('creator_id');
				$table->string('name')->nullable();
				$table->string('ar_name')->nullable();
				$table->integer('parent_id')->default(0);
				$table->string('serial')->nullable();
				$table->enum('slug',
					[
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
						'cost_of_goods_sale',
						'expenses',
						'product_sales_discount',
						'services_return_sales',
						'services_sales',
						'products_return_sales',
						'products_sales',
						'net_sales',
						'income',
						'vat'
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
			Schema::dropIfExists('charts');
		}
	}
