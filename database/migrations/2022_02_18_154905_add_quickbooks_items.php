<?php

use App\Jobs\QuickBooks\CustomerSyncJob;
use App\Jobs\QuickBooks\ItemSyncJob;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddQuickbooksItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string("quickbooks_id")->nullable();
        });
        foreach (\App\Models\Item::whereIsKit(false)->with("organization")->get() as $item) {
            dispatch_sync(new ItemSyncJob($item, Manager::whereEmail("ali@msbrshop.com")->first()));
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
