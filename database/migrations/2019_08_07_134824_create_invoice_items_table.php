<?php

	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;

	class CreateInvoiceItemsTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{ 
			Schema::create('invoice_items',function (Blueprint $table){
				$table->bigIncrements('id');
				
				$table->integer('organization_id');
				$table->integer('invoice_id');
				$table->integer('creator_id');
				$table->integer('item_id');
				$table->integer('user_id');
				
				$table->integer("returned_qty")->default(0);
				$table->float('discount',20,2)->default(0);

				$table->integer("qty")->default(0);
				$table->float("tax",20,2)->default(0);
				$table->float('price',20,2)->default(0);
				$table->float('net',20,2)->default(0);
				$table->float('total',20,2)->default(0);
				$table->float('subtotal',20,2)->default(0);
				$table->enum("invoice_type",['purchase','sale','retrun_sale','return_purchase','beginning_inventory'])->nullable();
				// ,'quotation','pending_purchase','stock_adjust'
				// $table->enum('type',['new',"return",'quotation']);
				$table->boolean('belong_to_kit')->default(false);
				$table->integer('parent_kit_id')->default(0);
				$table->boolean('is_kit')->default(false);
				$table->boolean('show_price_in_print_mode')->default(true);
				$table->boolean('is_draft')->default(false);
				$table->integer("available_qty")->default(0);
				$table->float('cost',20,2)->default(0)->nullable();
				$table->float('total_stock_cost_amount',20,2)->default(0)->nullable();
				$table->float("profit",20,2)->default(0);
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
			Schema::dropIfExists('invoice_items');
		}
	}
