<?php

namespace Modules\Sales\Tests\Feature\Sale;

use App\Models\Account;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Tests\TestCase;

class SalesWithKitOnlyTest extends TestCase
{


    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function testSuccessCreateSalesWithItemsIncludingKitsOnly()
    {
        $client = User::where('is_client', true)->inRandomOrder()->first();
        $salesman = Manager::inRandomOrder()->first();
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $items = Item::kit()->InRandomOrder()->take(1)->get();
        $this->assertCount(1, $items);
        $this->assertIsIterable($items);
        $queryItems = [];
        foreach ($items as $item) {
            $this->assertInstanceOf(Item::class, $item);
            $item->discount = rand(0, 2);
            $item->prinable = rand(0, 1);
            $item->qty = rand(1, 2);
            $queryItems[] = $item->toArray();
        }

        $this->assertIsArray($queryItems);
        $data = [
            'items' => $queryItems,
            'salesman_id' => $salesman->id,
            'client_id' => $client->id,
            'methods' => [$tempResellerAccount->toArray()]
        ];

        $headers = [
            'accept' => 'application/json'
        ];
        $response = $this->post('/sales', $data, $headers);
//        if ($response->status() !== 200) {
            $response->dump();
//        }
        $response->assertOk();
    }




}
