<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeTablesEnumsToStrings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE accounts ALTER COLUMN slug Type VARCHAR");
        DB::statement("ALTER TABLE accounts ALTER COLUMN type Type VARCHAR");
        DB::statement("ALTER TABLE transactions ALTER COLUMN type Type VARCHAR");

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
