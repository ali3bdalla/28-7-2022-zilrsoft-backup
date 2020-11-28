<?php
	
	namespace App\Http\Controllers\Api;
	
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Item\UploadItemImagesRequest;
	use App\Http\Requests\Items\FetchItemsRequest;
	use App\Http\Requests\Items\QueryItemsRequest;
	use App\Http\Requests\Items\ValidateSerialRequest;
	use App\Http\Resources\InvoiceItem\InvoiceItemCollection;
	use App\Models\Attachment;
	use App\Models\Item;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Validation\ValidationException;
	use League\CommonMark\Inline\Element\Strong;
	
	class ItemController extends Controller
	{
		//
		
		public function index(FetchItemsRequest $request)
		{
			return $request->getData();
		}
		
		public function transactions(Item $item, Request $request)
		{
			$query = $item->pipeline();
//
			
			if(
				$request->has('start_at') && $request->filled('start_at') && $request->has('end_at') &&
				$request->filled('end_at')
			) {
				$_startDate = Carbon::parse($request->input("start_at"));
				$_endDate = Carbon::parse($request->input("end_at"));
				
				if($_endDate === $_startDate) {
					$query = $query->whereDate('created_at', $_startDate);
				} else {
					$query = $query->whereBetween(
						'created_at', [
							$_startDate,
							$_endDate,
						]
					);
				}
			}
			
			
			if($request->has('perPage') && $request->filled('perPage') && intval($request->input("perPage")) >= 1) {
				$items = $query->with('user', 'creator')->paginate((int)($request->input('perPage')));
			} else {
				$items = $query->with('user', 'creator')->paginate(50);
				
			}
			
			
			return new InvoiceItemCollection($items);
		}
		
		
		public function ValidateSalesSerial(ValidateSerialRequest $request)
		{
			return $request->sale();
		}
		
		public function ValidateReturnSalesSerial(ValidateSerialRequest $request)
		{
			return $request->returnSale();
		}
		
		
		public function ValidatePurchasesSerial(ValidateSerialRequest $request)
		{
			return $request->purchase();
		}
		
		
		public function ValidateReturnPurchasesSerial(ValidateSerialRequest $request)
		{
			return $request->returnPurchase();
		}
		
		public function querySearch(QueryItemsRequest $request)
		{
			return $request->results();
		}
		
		
		/**
		 * @param Request $request
		 *
		 * @return mixed
		 */
		public function validateUniqueBarcode(Request $request)
		{
			$request->validate(
				[
					'barcode' => 'required|string|min:4|organization_unique:App\Models\Item,barcode'
				]
			);
		}
		
		
		public function getImages(Item $item)
		{
		
		}
		
		
		public function uploadImages(Item $item, UploadItemImagesRequest $uploadItemImageRequest)
		{
			foreach($uploadItemImageRequest->file('images') as $requestImage)
				$uploadItemImageRequest->createImage_ReturnImageInstance($requestImage, $item);
			return back();
		}
		
		
		public function deleteImage(Item $item, Attachment $image)
		{
			Storage::disk('spaces')->delete($image->actual_path);
			$image->forceDelete();
		}
		
		
		public function updateDescription(Request $request, Item $item)
		{
			$request->validate(
				[
					'description' => 'required|string|min:20',
					'ar_description' => 'required|string|min:20',
				]
			);
			
			
			$item->update($request->only('description', 'ar_description'));
			return back();
		}
	}
