<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSlugNameToItemsAndCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('en_slug')->nullable()->unique();
            $table->string('ar_slug')->nullable()->unique();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('en_slug')->nullable()->unique();
            $table->string('ar_slug')->nullable()->unique();
        });
        $items = DB::table('items')->get();
        foreach ($items as $item) {
            DB::table('items')->where('id', $item->id)->update([
                'en_slug' => str_replace(" ", "-", $item->name . '-' . ' ' . $item->id),
                'ar_slug' => str_replace(' ', '-', $item->ar_name . '-' . ' ' . $item->id),
            ]);
        }


        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            DB::table('categories')->where('id', $category->id)->update([
                'en_slug' => str_replace(' ', '-', $category->name . '-' . ' ' . $category->id),
                'ar_slug' => str_replace(' ', '-', $category->ar_name . '-' . ' ' . $category->id),
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
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('en_slug');
            $table->dropColumn('ar_slug');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('en_slug');
            $table->dropColumn('ar_slug');
        });
    }
}
