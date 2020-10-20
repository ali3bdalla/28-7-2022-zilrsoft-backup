<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('creator_id');
            $table->integer('organization_id');
            $table->integer('account_id');
            $table->enum('type', ['credit', 'debit'])->default('debit');
            $table->integer('user_id')->nullable();
            $table->integer('item_id')->nullable();

            $table->float('amount', 20, 8);
            $table->boolean("is_manual")->default(false);
            $table->boolean("is_pending")->default(false);
            $table->integer('invoice_id')->nullable();
            $table->integer('container_id')->nullable();
            $table->string('description')->nullable();


            $table->float('total_debit_amount', 20, 8)->default(0);
            $table->float('total_credit_amount', 20, 8)->default(0);

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
        Schema::dropIfExists('transactions');
    }
}
