<?php

use App\Attachment;
use App\Models\Item;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//			;

        foreach (Item::all() as $item) {
            $item->attachments()->saveMany(factory(Attachment::class, 3)->make());

        }
//			$this->call(RoleAndPermissionSeeder::class);
//			Artisan::all('database:first-init');
//    	factory(App\Models\Country::class, 10)->create();
//    	factory(App\Models\Type::class, 10)->create();
//        factory(App\Role::class, 10)->create();
//        // factory(App\Category::class, 10)->create();
        // factory(App\Models\Filter::class, 10)->create();
        // factory(App\CategoryFilters::class, 10)->create();
        // $this->call(UsersTableSeeder::class);
    }
}
