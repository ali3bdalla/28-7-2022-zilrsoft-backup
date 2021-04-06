<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeAllOldTransactionsStatusToBeCompleted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $date = Carbon::parse('2021/01/01');
        DB::table('transactions_containers')->whereDate('created_at', '<', $date)->update([
            'is_pending' => false
        ]);
        DB::table('transactions')->whereDate('created_at', '<', $date)->update([
            'is_pending' => false
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('be_completed', function (Blueprint $table) {
            //
        });
    }
}
