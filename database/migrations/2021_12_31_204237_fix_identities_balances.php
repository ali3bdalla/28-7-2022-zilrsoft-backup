<?php

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixIdentitiesBalances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clientAccount = Account::where('slug', 'clients')->first();
        $vendorAccount = Account::where('slug', 'vendors')->first();
        foreach (User::whereIsClient(true)->get() as $user) {
            $balance =  $user->getYearlyBalance($clientAccount);
            $user->update([
                'balance' => $balance
            ]);
        }
        foreach (User::whereIsVendor(true)->get() as $user) {
            $balance =   $user->getYearlyBalance($vendorAccount);
            $user->update([
                'vendor_balance' => $balance
            ]);
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
