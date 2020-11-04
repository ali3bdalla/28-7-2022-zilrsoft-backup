<?php
	
	namespace Tests\Feature\Product;
	
	use App\Models\Attachment;
	use App\Models\Item;
	use App\Models\Manager;
	use Illuminate\Foundation\Testing\WithFaker;
	use Illuminate\Support\Facades\Session;
	use Illuminate\Support\Facades\Storage;
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
			$response = $this->actingAs($manager)->withSession(['IMAGE_UPLOAD_PASSWORD' => env('IMAGE_UPLOAD_PASSWORD')])->postJson(
				"/api/upload_images/{$product->id}", [
					'images' => [$firstImageToUpload, $secondImageToUpload]
				]
			);
			
			$response->assertRedirect();
			$this->assertGreaterThan($productAttachmentCount, $product->attachments()->count());
			$image = Attachment::orderBy('id','desc')->first();
			Storage::disk('spaces')->assertExists($image->actual_path);
		}
	}
