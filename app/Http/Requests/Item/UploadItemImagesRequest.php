<?php
	
	namespace App\Http\Requests\Item;
	
	use Illuminate\Foundation\Http\FormRequest;
	use Illuminate\Validation\ValidationException;
	
	class UploadItemImagesRequest extends FormRequest
	{
		/**
		 * Determine if the user is authorized to make this request.
		 *
		 * @return bool
		 */
		public function authorize()
		{
			return true;
		}
		
		/**
		 * Get the validation rules that apply to the request.
		 *
		 * @return array
		 */
		public function rules()
		{
			return [
				'images' => 'required|array|max:4',
				'images.*' => 'required|image|max:1000',
			];
		}
		
		public function createImage_ReturnImageInstance($requestImage, $item)
		{
			$this->validateUploadedFilesCount($item);
			$isMaster = false;
			$imagePath = $this->uploadItemImageAndReturnPath($requestImage);
			$itemImage = $item->attachments()->create(['is_master' => $this->getIsMaster($item, $isMaster), 'actual_path' => $imagePath, 'url' => $this->getUploadedImageUrl($imagePath)]);
			$itemImage->removePrevMasterAttachment($item);
			return $itemImage;
		}
		
		public function validateUploadedFilesCount($item)
		{
			if($item->attachments()->count() >= 4)
				throw ValidationException::withMessages(['images' => 'product has 4 images']);
		}
		
		public function uploadItemImageAndReturnPath($requestImage)
		{
			return $requestImage->store('images/items', 'public');
		}
		
		public function getIsMaster($item, $isMaster = false)
		{
			if($item->attachments()->count())
				return $isMaster;
			
			return true;
		}
		
		public function getUploadedImageUrl($imagePath)
		{
			return config('filesystems.disks.spaces.cdn_base_link') . '/' . $imagePath;
		}
	}
