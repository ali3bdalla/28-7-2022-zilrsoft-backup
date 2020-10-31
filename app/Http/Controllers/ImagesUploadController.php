<?php
	
	namespace App\Http\Controllers;
	
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
				return redirect('/images_upload');
			}
			
			
			return back()->withErrors(
				[
					
					'password' => 'password value not correct!'
				
				]
			);
			
			
		}
		
		
		public function index()
		{
			$items = Item::paginate(20);
			return view('images_uploads.index', compact('items'));
		}
		
		public function show(Item $item)
		{
			
			return Inertia::render('ImagesUpload/Show',[
				'item' => $item,
				'attachments' =>$item->attachments()->get()
			]);
		}
	}
