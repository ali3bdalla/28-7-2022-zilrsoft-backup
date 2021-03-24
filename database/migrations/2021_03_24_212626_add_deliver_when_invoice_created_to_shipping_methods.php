<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDeliverWhenInvoiceCreatedToShippingMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            //
            $table->boolean('deliver_when_invoice_created')->default(false);
        });

        DB::table('shipping_methods')->where('id', 5)->update([
            'deliver_when_invoice_created' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_methods', function (Blueprint $table) {
            //
            $table->dropColumn('deliver_when_invoice_created');
        });
    }
}
