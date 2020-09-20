<?php
	
	use Illuminate\Support\Facades\Schema;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Database\Migrations\Migration;
	
	class CreateInvoicesTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('invoices',function (Blueprint $table){
				$table->bigIncrements('id');
				$table->integer('organization_id');
				$table->integer('branch_id');
				$table->integer('department_id');
				$table->integer('creator_id');
				$table->boolean('is_draft')->default(false);
				// $table->enum('issued_status',["credit","paid"])->default("paid");
				// $table->enum('current_status',["credit","paid"])->default("paid");
				// $table->enum('invoice_type',['purchase',"return_purchase","sale","return_sale","quotation","beginning_inventory","inventory_count","stock_adjust",'pending_purchase']);
				
				$table->enum('invoice_type',['purchase',"return_purchase","sale","return_sale"]);
				//,"quotation","beginning_inventory","inventory_count","stock_adjust",'pending_purchase'

				$table->float("discount",20,8)->nullable();
				$table->float("total",20,8)->default(0);
				$table->float("subtotal",20,8)->default(0);
				$table->float("remaining",20,8)->default(0);
				$table->float("net",20,8)->default(0);
				$table->float("tax",20,8)->default(0);
				$table->float("vts",20,8)->default(0);
				$table->float("vtp",20,8)->default(0);
			
				$table->integer('user_id')->default(0);
				$table->integer('managed_by_id')->default(0);
				$table->integer('parent_id')->default(0);
				$table->boolean('show_items_price_in_print_mode')->default(true);
				$table->text('notes')->nullable();
				$table->string('invoice_number')->nullable();
				$table->boolean('is_deleted')->default(false);
				$table->boolean('is_updated')->default(false);
				
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
			Schema::dropIfExists('invoices');
		}
	}
