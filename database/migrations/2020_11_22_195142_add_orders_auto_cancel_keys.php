<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrdersAutoCancelKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('orders',function(Blueprint  $table){
	       $table->timestamp('auto_cancel_at')->nullable();
	       $table->boolean('is_should_pay_notified')->default(true);
	       $table->timestamp('should_pay_last_notification_at')->nullable();
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
