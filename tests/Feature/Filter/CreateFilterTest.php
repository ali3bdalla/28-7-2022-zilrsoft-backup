<?php
	
	namespace Tests\Feature\Filter;
	
	use App\Models\Manager;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class CreateFilterTest extends TestCase
	{
		
		use WithFaker;
		
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testCreateFilter_RedirectToFiltersPage()
		{
			$manager = Manager::inRandomOrder()->first();
			$response = $this->actingAs($manager)->postJson(
				'/api/filters', [
					'name' => $this->faker->name,
					'ar_name' => $this->faker->name,
					'required_filter' => $this->faker->boolean()
				]
			);
			$response->dump()->assertRedirect('/filters');
		}
	}
