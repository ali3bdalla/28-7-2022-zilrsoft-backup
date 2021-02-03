<?php

	namespace App\Http\Controllers\Api\Store;

	use App\Http\Controllers\Controller;
	use App\Models\Category;
	use Illuminate\Http\Request;

	class CategoryController extends Controller
	{
		public function index()
		{
			return Category::all();
		}

        public function subcategories(Category  $category)
        {
            return $category->children()->withCount('children')->get();
		}
	}
