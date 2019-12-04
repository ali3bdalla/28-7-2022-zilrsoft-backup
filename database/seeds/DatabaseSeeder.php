<?php
	
	use Illuminate\Database\Seeder;
	use Illuminate\Support\Facades\Artisan;
	use Illuminate\Support\Facades\DB;
	
	class DatabaseSeeder extends Seeder
	{
		/**
		 * Seed the application's database.
		 *
		 * @return void
		 */
		public function run()
		{
			;
			
			Artisan::all('database:first-init');
//    	factory(App\Country::class, 10)->create();
//    	factory(App\Type::class, 10)->create();
//        factory(App\Role::class, 10)->create();
//        // factory(App\Category::class, 10)->create();
			// factory(App\Filter::class, 10)->create();
			// factory(App\CategoryFilters::class, 10)->create();
			// $this->call(UsersTableSeeder::class);
		}
	}
