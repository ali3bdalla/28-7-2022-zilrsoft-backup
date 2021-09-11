<?php

use App\Jobs\Utility\Str\ReplaceArabicSensitiveCharJob;
use App\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReplaceArabicSencetiveChars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Item::withoutEvents(function(){
            $items = Item::all();
            foreach ($items as $item) {
                $item->update([
                    'ar_name' => ReplaceArabicSensitiveCharJob::dispatchSync($item->ar_name)
                ]);
            }
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
