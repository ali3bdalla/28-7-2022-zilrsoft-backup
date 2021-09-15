<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddVendorInvoiceIdToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('vendor_invoice_id')->nullable();
        });

        $purchases = DB::table('purchases')->whereNotNull('vendor_invoice_id')->get();
        foreach ($purchases as $purchase) {
            DB::table('invoices')->where('id', $purchase->invoice_id)->update([
                'vendor_invoice_id' => $purchase->vendor_invoice_id
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('vendor_invoice_id');
        });
    }
}
