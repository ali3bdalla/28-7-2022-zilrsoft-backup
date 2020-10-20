<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerialHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serial_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('serial_id');
            $table->integer('invoice_id');
            $table->integer('organization_id');
            $table->string('event')->nullable();
            $table->integer('user_id');
            $table->integer('creator_id');
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
        Schema::dropIfExists('serial_histories');
    }
}
