<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKitDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kit_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('kit_id');
            $table->float('total', 20, 8)->default(0);
            $table->float('tax', 20, 8)->default(0);
            $table->float('net', 20, 8)->default(0);
            $table->float('subtotal', 20, 8)->default(0);
            $table->float('discount', 20, 8)->default(0);
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
        Schema::dropIfExists('kit_data');
    }
}
