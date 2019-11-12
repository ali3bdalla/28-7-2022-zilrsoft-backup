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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->integer('chart_id')->default(0);
            $table->integer('organization_id');
            $table->integer('branch_id');
            $table->integer('department_id');
            $table->integer('creator_id');
            $table->enum('issued_status',["credit","paid"])->default("paid");
            $table->enum('current_status',["credit","paid"])->default("paid");
            $table->enum('invoice_type',['purchase',"r_purchase","sale","r_sale","quotation","beginning_inventory","inventory_count","stock_adjust"]);


            $table->float("discount_value",20,8)->nullable();
            $table->float("discount_percent",20,8)->nullable();
            $table->float("total",20,8)->default(0);
            $table->float("subtotal",20,8)->default(0);
            $table->float("remaining",20,8)->default(0);
            $table->float("net",20,8)->default(0);
            $table->float("tax",20,8)->default(0);
            $table->float("vts",20,8)->default(0);
            $table->float("vtp",20,8)->default(0);


            $table->string('pdf_invoice')->nullable();
            $table->integer('parent_invoice_id')->default(0);



            $table->boolean('is_deleted')->default(false);
            $table->boolean('is_updated')->default(false);


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
