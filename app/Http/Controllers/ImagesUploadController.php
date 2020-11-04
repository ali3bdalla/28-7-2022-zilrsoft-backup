<?php
	
	namespace App\Http\Controllers;
	
	use App\Models\Category;
	use App\Models\Item;
	use Illuminate\Http\Request;
	use Inertia\Inertia;
	
	class ImagesUploadController extends Controller
	{
		//
		public function auth()
		{
			return Inertia::render('ImagesUpload/Auth');
		}
		
		
		public function redirectInertia()
		{
			return Inertia::render('ImagesUpload/Redirect');
		}
		
		public function grantAuthorization(Request $request)
		{
			$actualPassword = env('IMAGE_UPLOAD_PASSWORD');
			$request->validate(
				[
					'password' => "required|string"
				]
			);
			
			if($request->input('password') == $actualPassword) {
				$request->session()->push('IMAGE_UPLOAD_PASSWORD', $actualPassword);
				return redirect('/images_upload/redirect');
			}
			
			
			return back()->withErrors(
				[
					
					'password' => 'password value not correct!'
				
				]
			);
			
			
		}
		
		
		public function index(Request $request)
		{
			$items = Item::query();
			
			
			$categoryId = 0;
			if($request->has('category_id')) {
				
				$categoryId = $request->input('category_id');
				$category = Category::find($categoryId);
				if($category) {
					$items->whereIn('category_id', $category->getChildrenIncludeMe());
				}
			}
			
			
			$itemsCount = $items->count();
			$items = $items->withCount('attachments')->paginate(20)->each(
				function($item) {
					$filter = $item->filters()->where('filter_id', 38)->first();
					if($filter && $filter->value) {
						$item->model_name = $filter->value->name;
						$item->model_ar_name = $filter->value->ar_name;
					}
					
					return $item;
				}
			);
			
			
			return $items;
			$chats = Category::where('parent_id', 0)->get();
			$categories = [];
			foreach($chats as $category) {
				$category['children'] = Category::getAllParentNestedChildren($category);
				$categories[] = $category;
			}
			
			return view('images_uploads.index', compact('items', 'itemsCount', 'categories', 'categoryId'));
		}
		
		public function show(Item $item)
		{
			
			return Inertia::render(
				'ImagesUpload/Show', [
					'item' => $item,
					'attachments' => $item->attachments()->get()
				]
			);
		}
	}
