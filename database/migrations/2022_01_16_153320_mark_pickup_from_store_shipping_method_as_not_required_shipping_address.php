<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MarkPickupFromStoreShippingMethodAsNotRequiredShippingAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('shipping_methods')->where('id', 5)->update([
            "is_required_shipping_address" => false
        ]);
    }
}
