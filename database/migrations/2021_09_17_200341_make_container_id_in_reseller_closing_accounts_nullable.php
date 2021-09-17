<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MakeContainerIdInResellerClosingAccountsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE reseller_closing_accounts ALTER COLUMN container_id DROP NOT NULL");
        DB::statement("ALTER TABLE reseller_closing_accounts ALTER COLUMN receiver_id DROP NOT NULL");
        DB::statement("ALTER TABLE transactions_containers ALTER COLUMN invoice_id DROP NOT NULL");
        DB::statement("ALTER TABLE transactions ALTER COLUMN invoice_id DROP NOT NULL");
        DB::statement("ALTER TABLE transactions ALTER COLUMN item_id DROP NOT NULL");
        DB::statement("ALTER TABLE transactions ALTER COLUMN user_id DROP NOT NULL");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
