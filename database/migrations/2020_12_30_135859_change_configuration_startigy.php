<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeConfigurationStartigy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            //
            $table->dropColumn('organization_id');
            $table->dropColumn('switch_online_store');
            $table->morphs('configurable');
            $table->string('key')->nullable();
            $table->string('title')->nullable();
            $table->text('value')->nullable();
            $table->string('type')->nullable();
            $table->string('collection')->nullable();
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
