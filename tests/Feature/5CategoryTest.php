<?php
//
//namespace Tests\Feature;
//
//use App\Country;
//use App\Type;
//use Illuminate\Foundation\Testing\RefreshDatabase;
//use Illuminate\Foundation\Testing\WithFaker;
//use Tests\TestCase;
//
//class CategoryTest extends TestCase
//{
//	use WithFaker;
//
//	protected function setUp():void
//	{
//		parent::setUp(); // TODO: Change the autogenerated stub
//		auth()->loginUsingId(1);
//	}
//
//	/**
//     * A basic feature test example.
//     * @test
//     * @return void
//     */
//    public function toCreate()
//    {
//	    $response = $this->json('post',route('management.categories.store'),[
//		    'name' => $this->faker->name,
//		    'ar_name' => $this->faker->name,
//		    'description' => $this->faker->sentence,
//		    'ar_description' => $this->faker->sentence,
//		    'parent_id' => 0
//
//	    ]);
//
////	    $response->dump();
//
//	    $response->assertStatus(302);
//    }
//}
