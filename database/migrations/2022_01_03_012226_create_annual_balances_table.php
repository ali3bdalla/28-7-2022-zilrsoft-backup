<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnualBalancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('annual_balances', function (Blueprint $table) {
            $table->id();
            $table->year('year')->nullable();
            $table->morphs('account');
            $table->string('slug')->nullable();
            $table->float('balance')->default(0);
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
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
        Schema::dropIfExists('annual_balances');
    }
}
