<?php
	
	namespace Tests\Feature\Product;
	
	use App\Models\Item;
	use App\Models\Manager;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Support\Facades\Session;
	use Tests\TestCase;
	use Illuminate\Http\UploadedFile;
	
	class UploadProductImagesTest extends TestCase
	{
		use WithFaker;
		
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function test_upload_product_images()
		{
			
			$product = Item::InRandomOrder()->first();
			$manager = Manager::InRandomOrder()->first();
			$productAttachmentCount = $product->attachments()->count();
			$firstImageToUpload = UploadedFile::fake()->image('image.jpg', 400, 500)->size(1000);
			$secondImageToUpload = UploadedFile::fake()->image('image.jpg', 1000, 1000)->size(1000);
			Session::put('IMAGE_UPLOAD_PASSWORD', env('IMAGE_UPLOAD_PASSWORD'));
			$this->actingAs($manager)->postJson(
				"/api/upload_images/{$product->id}", [
					'images' => [$firstImageToUpload, $secondImageToUpload]
				]
			);
			$this->assertGreaterThan($productAttachmentCount, $product->attachments()->count());
		}
	}
