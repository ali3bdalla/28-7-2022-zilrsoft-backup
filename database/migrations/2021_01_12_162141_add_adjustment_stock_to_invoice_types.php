<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAdjustmentStockToInvoiceTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("alter type invoices_invoice_type ADD VALUE IF NOT EXISTS 'inventory_adjustment'");
        DB::statement("alter type invoice_items_invoice_type ADD VALUE  IF NOT EXISTS 'inventory_adjustment'");
        DB::statement("alter type purchases_invoice_type ADD VALUE  IF NOT EXISTS 'inventory_adjustment'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
