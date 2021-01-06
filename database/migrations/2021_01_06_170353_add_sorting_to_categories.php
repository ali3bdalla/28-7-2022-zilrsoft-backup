<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortingToCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       foreach(Category::all() as $category)
        {
            $category->update([
                'sorting'=> $category->id
            ]);
        }
    }

  
}
