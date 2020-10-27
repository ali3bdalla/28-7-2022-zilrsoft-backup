<?php
	
	namespace App\Http\Controllers\Api\Web;
	
	use App\Http\Controllers\Controller;
	use App\Models\Category;
	use Illuminate\Http\Request;
	
	class CategoryController extends Controller
	{
		public function index()
		{
			return Category::all();
		}
	}
