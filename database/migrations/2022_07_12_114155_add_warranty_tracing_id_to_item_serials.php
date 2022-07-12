<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarrantyTracingIdToItemSerials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_serials', function (Blueprint $table) {
            //
            $table->bigInteger("warranty_tracing_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_serials', function (Blueprint $table) {
            $table->dropColumn("warranty_tracing_id");
        });
    }
}
