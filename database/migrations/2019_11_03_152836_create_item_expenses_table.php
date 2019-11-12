<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_expenses', function (Blueprint $table) {
	        $table->bigIncrements('id');
	
	        $table->integer('organization_id');
	        $table->integer('item_id');
	        $table->integer('creator_id');
	        $table->integer('expense_id');
	        $table->integer('invoice_id');
	        $table->float('amount',20,2);
	        $table->boolean('is_paid')->default(false);
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
        Schema::dropIfExists('item_expenses');
    }
}
