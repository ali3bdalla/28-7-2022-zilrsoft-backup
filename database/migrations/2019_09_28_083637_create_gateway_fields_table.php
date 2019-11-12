<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatewayFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_fields', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('gateway_id');
            $table->string('placeholder');
            $table->string('ar_placeholder');
            $table->enum('type',['list','text','password'])->default('text');
            $table->enum('bind_vue_name',['account','account_name'])->default('account');
            $table->text('list-data')->nullable();
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
        Schema::dropIfExists('gateway_fields');
    }
}
