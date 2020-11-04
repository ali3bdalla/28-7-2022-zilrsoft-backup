<?php
	
	namespace Tests\Feature\Filter;
	
	use App\Models\Filter;
	use App\Models\Manager;
	use Illuminate\Foundation\Testing\RefreshDatabase;
	use Illuminate\Foundation\Testing\WithFaker;
	use Tests\TestCase;
	
	class EditFilterTest extends TestCase
	{
		use WithFaker;
		/**
		 * A basic feature test example.
		 *
		 * @return void
		 */
		public function testEditFilter_RedirectToFiltersPage()
		{
			$manager = Manager::inRandomOrder()->first();
			$filter = Filter::inRandomOrder()->first();
			$response = $this->actingAs($manager)->patchJson(
				"/api/filters/{$filter->id}", [
					'name' => $this->faker->name,
					'ar_name' => $this->faker->name,
					'required_filter' => $this->faker->boolean()
				]
			);
			$response->dump()->assertRedirect('/filters');
		}
	}
