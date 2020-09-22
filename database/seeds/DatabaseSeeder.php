<?php

use App\Models\Manager;
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

        factory(App\Models\Country::class, 3)->create();
        factory(App\Models\Type::class, 2)->create();
        factory(App\Models\Manager::class)->create([
            'email' => 'developer@dev.com'
        ]);
        factory(App\Models\Manager::class)->create([
            'email' => 'tester@dev.com'
        ]);

        factory(App\Models\Manager::class, 5)->create();
        factory(App\Models\Category::class, 2)->create();
        factory(App\Models\User::class, 5)->create();
        factory(App\Models\Organization::class)->create();
        factory(App\Models\Item::class, 10)->create();
        factory(App\Models\Item::class, 5)->create([
            'is_need_serial' => true,
        ]);

    }
}
