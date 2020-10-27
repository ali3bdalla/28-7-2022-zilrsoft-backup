<?php
	
	namespace App\Http\Controllers\Api\Web;
	
	use App\Http\Controllers\Controller;
	use App\Models\Item;
	use Illuminate\Http\Request;
	
	class CartController extends Controller
	{
		public function getItemDetails(Request $request)
		{
			$request->validate(
				[
					'items' => 'array',
					'items.*' => 'required|integer|exists:items,id'
				]
			);
			
			return Item::find((array)$request->input('items'));
		}
	}
