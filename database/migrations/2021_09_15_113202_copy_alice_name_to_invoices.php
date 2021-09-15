<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CopyAliceNameToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('user_alice_name')->nullable();
        });
        $sales = DB::table('sales')->whereNotNull('alice_name')->get();
        foreach ($sales as $sale) {
            DB::table('invoices')->where('id', $sale->invoice_id)->update([
                'user_alice_name' => $sale->alice_name
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
            $table->dropColumn('user_alice_name');
        });
    }
}
