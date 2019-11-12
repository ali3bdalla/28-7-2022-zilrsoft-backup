<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationGatewayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_gateway', function (Blueprint $table) {
	        $table->bigIncrements('id');
	        $table->unsignedBigInteger('organization_id');
	        $table->unsignedBigInteger('gateway_id');
	        $table->unsignedBigInteger('creator_id');
	        $table->foreign('organization_id')->on('organizations')->references('id');
	        $table->foreign('gateway_id')->on('gateways')->references('id');
	        $table->foreign('creator_id')->on('managers')->references('id');
	     
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
        Schema::dropIfExists('organization_gateway');
    }
}
