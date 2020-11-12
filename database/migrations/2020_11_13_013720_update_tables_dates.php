<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTablesDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
//	    ALTER TABLE {tablename} ALTER updated_at SET DATA TYPE timestamp(0) without time zone;
	    $tables = DB::select("SELECT table_schema,table_name, table_catalog FROM information_schema.tables WHERE table_catalog = 'YOUR TABLE CATALOG HERE' AND table_type = 'BASE TABLE' AND table_schema = 'zilrsoft_production' ORDER BY table_name;");
	    
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
