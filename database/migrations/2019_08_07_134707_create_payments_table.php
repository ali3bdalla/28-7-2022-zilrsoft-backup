<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');
            $table->integer('creator_id');
            $table->integer('invoice_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('account_id');
            $table->float("amount", 20, 8)->default(0);
            $table->string('description')->nullable();
            $table->enum("payment_type", ['receipt', "payment"]);
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
        Schema::dropIfExists('payments');
    }
}
