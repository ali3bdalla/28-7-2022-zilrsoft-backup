<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResellerClosingAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reseller_closing_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('organization_id');

            $table->enum('transaction_type', ['close_account', 'transfer']);

            $table->integer('container_id');
            $table->dateTime('from')->nullable();
            $table->dateTime('to')->nullable();
            $table->boolean('is_pending')->default(false);
            $table->float('amount', 20, 8)->default(0);
            $table->integer('creator_id');
            $table->integer('receiver_id')->default(0);

            $table->float('shortage_amount', 20, 8)->nullable();


            $table->float('remaining_amount', 20, 8)->nullable();

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
        Schema::dropIfExists('reseller_closing_accounts');
    }
}
