<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBalanceTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balance_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
	        $table->integer('organization_id');
	        $table->integer('user_id');
	        $table->float('amount',20,2);
	        $table->string('transaction_type');
	        $table->integer('transaction_id');
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
        Schema::dropIfExists('user_balance_transactions');
    }
}
