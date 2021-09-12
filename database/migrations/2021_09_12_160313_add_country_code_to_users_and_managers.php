<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Propaganistas\LaravelPhone\PhoneNumber;

class AddCountryCodeToUsersAndManagers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->get()->each(function($user){
            DB::table('users')->whereId($user->id)->update([
                'country_code' => 'SA',
                'phone_number' => PhoneNumber::make($user->phone_number)->ofCountry("SA")
            ]);
        });

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
