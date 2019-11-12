<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGatewayAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->string('account');
	        $table->string('account_name')->nullable();
            $table->integer('bank_id')->nullable();
            $table->integer('gateway_id');
            $table->integer('accountable_id');
            $table->string('accountable_type');
            
            $table->softDeletes();
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
        Schema::dropIfExists('organization_gateways_accounts');
    }
}
