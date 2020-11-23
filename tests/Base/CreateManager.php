<?php
	
	
	namespace Tests\Base;
	
	
	use App\Jobs\Accounting\Chart\CreateAmericanChartOfAccountsJob;
	use App\Models\Manager;
	use App\Models\Organization;
	use App\Models\User;
	use Illuminate\Foundation\Testing\WithFaker;
	
	trait CreateManager
	{
		use WithFaker;
		
		public function getManager($id)
		{
			return Manager::find($id);
		}
		public function initOrganizationAndManager()
		{
			$organization = factory(Organization::class)->create();
			$organization->addTranslate(['en' => $this->faker->name, 'ar' => $this->faker->name], $this->faker->name, 'title');
			$organization->addTranslate(['en' => $this->faker->sentence, 'ar' => $this->faker->sentence], $this->faker->name, 'description');
			$manager = $this->createManager($organization->id);
			$organization->fill(['supervisor_id' => $manager->user_id]);
			$organization->save();
			dispatch(new CreateAmericanChartOfAccountsJob($organization, $manager));
			
			return $manager;
		}
		
		public function createManager($organizationId)
		{
			$user = factory(User::class)->create(
				[
					'organization_id' => $organizationId
				]
			);
			return factory(Manager::class)->create(
				[
					'user_id' => $user->id,
					'organization_id' => $organizationId
				]
			);
		}
		
		public function getAccount(Organization $organization, $slug = null)
		{
			$query = $organization->accounts();
			if($slug) {
				$query = $query->where('slug', $slug);
			}
			
			return $query->inRandomOrder()->first();
		}
	}