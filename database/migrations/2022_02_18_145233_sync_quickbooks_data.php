<?php

use App\Jobs\QuickBooks\CustomerSyncJob;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SyncQuickbooksData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean("has_quickbooks")->default(false);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string("quickbooks_customer_id")->nullable();
            $table->string("quickbooks_vendor_id")->nullable();
        });
        DB::table('organizations')->where('id', 1)->update([
            'has_quickbooks' => true
        ]);
        foreach (User::whereIsClient(true)->with("organization")->get() as $user) {
            dispatch_sync(new CustomerSyncJob($user, Manager::whereEmail("ali@msbrshop.com")->first()));
        }
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
