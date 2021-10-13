<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteUnusedTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists("configurations");
        Schema::dropIfExists("item_statistics");
        Schema::dropIfExists("item_expenses");
        Schema::dropIfExists("sales");
        Schema::dropIfExists("purchases");
        Schema::dropIfExists("exponent_push_notification_interests");
        Schema::dropIfExists("draft_invoices_activities");
        Schema::dropIfExists("order_item_qty_holders");
        Schema::dropIfExists("user_balance_snapshots");
        Schema::dropIfExists("password_resets");
        Schema::dropIfExists("personal_access_tokens");
        Schema::dropIfExists("order_activities");
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
