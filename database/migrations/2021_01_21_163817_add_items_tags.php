<?php

use App\Models\Item;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItemsTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $items = Item::all();
        foreach ($items as $item) {

            foreach ($item->filters as $key => $value) {
                if ($value->value) {
                    $item->tags()->create([
                        'tag' => $value->value->name
                    ]);

                    $item->tags()->create([
                        'tag' => $value->value->ar_name
                    ]);
                }
            }
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
