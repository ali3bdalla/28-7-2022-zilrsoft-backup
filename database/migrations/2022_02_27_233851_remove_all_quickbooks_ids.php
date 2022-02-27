<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveAllQuickbooksIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("update users set quickbooks_customer_id = null");
        DB::statement("update users set quickbooks_vendor_id = null");
        DB::statement("update items set quickbooks_id = null");
        DB::statement("update categories set quickbooks_id = null");
        DB::statement("update invoices set quickbooks_id = null");
        DB::statement("update managers set quickbooks_class_id = null");
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
