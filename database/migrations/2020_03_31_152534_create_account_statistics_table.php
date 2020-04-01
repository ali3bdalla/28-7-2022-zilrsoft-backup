<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_statistics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('account_id');
	        $table->integer('debit_transactions_count')->default(0);
	        $table->integer('credit_transactions_count')->default(0);
	        $table->float('debit_transactions_total_amount',50,8)->default(0);
	        $table->float('credit_transactions_total_amount',50,8)->default(0);
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
        Schema::dropIfExists('account_statistics');
    }
}
