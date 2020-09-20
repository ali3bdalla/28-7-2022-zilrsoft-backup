<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->integer('salesman_id');
            $table->integer('client_id');
            $table->integer('invoice_id');
            $table->string('alice_name')->nullable();
            // $table->boolean("is_full_returned")->default(0);
            // $table->boolean("is_returned")->default(0);
            $table->string('prefix',30)->nullable();
            $table->integer("parent_id")->default(0);
            $table->enum("invoice_type",['sale','return_sale'])->nullable();
            $table->boolean('is_draft')->default(false);
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
        Schema::dropIfExists('sales');
    }
}
