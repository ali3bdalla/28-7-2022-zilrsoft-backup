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
	use Illuminate\Http\Request;
	use Illuminate\Validation\ValidationException;
	
	class ItemController extends Controller
	{
		//
		
		public function index(FetchItemsRequest $request)
		{
			return $request->getData();
		}
		
		public function transactions(Item $item)
		{
			$items = $item->pipeline()->with('user', 'creator')->paginate(50);
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
					'barcode' => 'required|string|min:4|unique:items,barcode'
				]
			);
		}
		
		
		public function getImages(Item $item)
		{
		
		}
		
		
		public function uploadImages(Item $item, UploadItemImagesRequest $uploadItemImageRequest)
		{
			
			
			foreach($uploadItemImageRequest->file('images') as $requestImage) {
				$uploadItemImageRequest->validateUploadedFilesCount($item);
				$isMaster = false;
				$imagePath = $uploadItemImageRequest->uploadItemImageAndReturnPath($requestImage);
				$itemImage = $item->attachments()->create(['is_master' => $uploadItemImageRequest->getIsMaster($item, $isMaster), 'actual_path' => $imagePath, 'url' => $uploadItemImageRequest->getUploadedImageUrl($imagePath)]);
				$itemImage->removePrevMasterAttachment($item);
				
				$result[] = $itemImage;
			}
			
			return back();
		}
		
		
		public function deleteImage(Item $item, Attachment $attachment)
		{
		
		}
		
		
	}
