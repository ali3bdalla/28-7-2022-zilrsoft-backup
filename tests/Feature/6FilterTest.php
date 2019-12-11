<?php
//
//	namespace Tests\Feature;
//
//	use App\Filter;
//	use Illuminate\Foundation\Testing\WithFaker;
//	use Tests\TestCase;
//
//	class FilterTest extends TestCase
//	{
//
//		use WithFaker;
//
//		/**
//		 * A basic feature test example.
//		 *
//		 * @test
//		 * @return void
//		 */
//		public function toCreate()
//		{
//
//			$response = $this->json('post',route('management.filters.store'),[
//				'name' => $this->faker->domainName,
//				'ar_name' => $this->faker->domainName,
//
//
//			]);
//
//
//			$response->assertStatus(302);
//		}
//
//		/**
//		 * @test
//		 * */
//		public function toCreateValues()
//		{
//			$response = $this->json('post',route('management.filters.create_value'),[
//				'name' => $this->faker->domainName,
//				'ar_name' => $this->faker->domainName,
//				'filter_id' => Filter::inRandomOrder()->first()->id
//
//
//			]);
//
////			$response->dump();
//			$response->assertStatus(201);
//		}
//
//		protected function setUp():void
//		{
//			parent::setUp(); // TODO: Change the autogenerated stub
//			auth()->loginUsingId(1);
//		}
//	}