<?php

use App\Jobs\Accounting\Chart\CreateAmericanChartOfAccountsJob;
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
        factory(App\Models\Manager::class, 5)->create();
        factory(App\Models\Category::class, 2)->create();
        factory(App\Models\User::class, 5)->create();
        factory(App\Models\Organization::class)->create();
        // dispatch(new CreateAmericanChartOfAccountsJob($organization, Manager::first()));
        factory(App\Models\Item::class, 10)->create();
        factory(App\Models\Item::class, 5)->create([
            'is_need_serial' => true,
        ]);

        for ($i = 1; $i < 5; $i++) {
            $price = 0.5 * $i;
            $priceWithTax = $price + ($price * 15 / 100);
            factory(App\Models\Item::class)->create([
                'is_need_serial' => true,
                'price' => $price,
                'price_with_tax' => $priceWithTax,
            ]);
        }

    }
}
