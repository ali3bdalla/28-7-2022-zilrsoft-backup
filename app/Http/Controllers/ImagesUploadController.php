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
			
			$activeModel = 'all';
			if($request->has('active_model')) {
				
				$activeModel = $request->input('active_model');
			}
			
			
			$itemsCount = $items->count();
			$queryItems = $items->withCount('attachments')->paginate(40);
			$links = $queryItems->appends(['category_id' => $categoryId,'active_model' => $activeModel])->links();
			$completedProducts = Item::withCount('attachments')->having('attachments_count', 4)->get()->count();
			
			
			$items = [];
			foreach($queryItems as $item) {
				$filter = $item->filters()->where('filter_id', 38)->first();
				if($filter && $filter->value) {
					$item['model_name'] = $filter->value->name;
					$item['model_ar_name'] = $filter->value->ar_name;
				} else {
					$item['model_name'] = "";
					$item['model_ar_name'] = "";
				}
				
				
				if($activeModel == 'all')
					$items[] = $item;
				elseif($activeModel == 'empty') {
					if($item['model_name'] == "")
						$items[] = $item;
				} elseif($activeModel == 'not_empty') {
					if($item['model_name'] != "")
						$items[] = $item;
				}
			}

			$chats = Category::where('parent_id', 0)->get();
			
			$categories = [];
			foreach($chats as $category) {
				$category['children'] = Category::getAllParentNestedChildren($category);
				$categories[] = $category;
			}
			
			return view('images_uploads.index', compact('items', 'activeModel', 'itemsCount', 'categories', 'categoryId', 'links', 'completedProducts'));
		}
		
		public function show(Item $item)
		{

//			return  $item;
			return Inertia::render(
				'ImagesUpload/Show', [
					'item' => $item,
					'attachments' => $item->attachments()->get()
				]
			);
		}
	}
