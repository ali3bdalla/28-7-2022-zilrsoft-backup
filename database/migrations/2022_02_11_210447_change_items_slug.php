<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChangeItemsSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('en_slug');
            $table->dropColumn('ar_slug');
            $table->string('slug')->nullable()->unique();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('en_slug');
            $table->dropColumn('ar_slug');
            $table->string('slug')->nullable()->unique();
        });
        $items = DB::table('items')->get();
        foreach ($items as $item) {
            DB::table('items')->where('id', $item->id)->update([
                'slug' => Str::of($item->name)->append(' ',$item->id)->slug(),
            ]);
        }
        $categories = DB::table('categories')->get();
        foreach ($categories as $category) {
            DB::table('categories')->where('id', $category->id)->update([
                'slug' => Str::of($category->name)->append(' ' . $category->id)->slug(),
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
