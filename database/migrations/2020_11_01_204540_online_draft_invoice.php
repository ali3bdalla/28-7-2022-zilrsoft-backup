<?php
	
	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	
	class OnlineDraftInvoice extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::table(
				'invoices', function(Blueprint $table) {
				$table->boolean('is_online')->default(false);
				$table->boolean('is_draft_converted')->default(false);
			}
			);
			
			Schema::create(
				'draft_invoices_activities', function(Blueprint $table) {
				$table->bigIncrements('id')->primary()->autoIncrement();
				$table->integer('draft_id');
				$table->integer('invoice_id');
				$table->enum('activity', ['to_invoice'])->default('to_invoice');
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
			Schema::dropIfExists('draft_invoices_activities');
		}
	}
