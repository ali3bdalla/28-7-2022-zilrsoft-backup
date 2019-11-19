<?php
	
	namespace Tests\Feature;
	
	use App\Type;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class BusinessTest extends TestCase
	{
		use WithFaker;
		/**
		 * A basic feature test example.
		 *
		 * @test
		 * @return void
		 */
		public function toCreateNewBusinessType()
		{
			$new_type = new Type();
			$new_type->name = $this->faker->name;
			$new_type->ar_name = $this->faker->name;
			
			
			$this->assertTrue($new_type->save());
			
		}
	}
