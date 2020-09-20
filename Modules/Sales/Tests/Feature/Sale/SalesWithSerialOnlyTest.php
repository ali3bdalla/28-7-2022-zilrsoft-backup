<?php

namespace Modules\Sales\Tests\Feature\Sale;

use App\Models\Account;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Tests\TestCase;

class SalesWithSerialOnlyTest extends TestCase
{


    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function testSuccessCreateSalesWithItemsIncludingSerialsOnly()
    {
        $client = User::where('is_client', true)->inRandomOrder()->first();
        $salesman = Manager::inRandomOrder()->first();
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $items = Item::InRandomOrder()->where('available_qty', '>', 4)->withCount(['serials' => function($query){
            return $query->whereIn('current_status',['available','purchase','return_sale']);
        }])->having('serials_count',1)->hasSerial()->take(5)->get();
        $this->assertIsIterable($items);
        $queryItems = [];
        foreach ($items as $item) {
            $this->assertInstanceOf(Item::class, $item);
            $this->assertGreaterThanOrEqual(5, $item->available_qty);
            $item->qty = 1;
            $item->discount = rand(0, 2);
            $item->prinable = rand(0, 1);
            $item->serials = $item->serials()->whereIn('current_status',['available','purchase','return_sale'])->take(1)->pluck('serial')->toArray();
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
        if ($response->status() !== 200) {
            $response->dump();
        }
        $response->assertOk();
    }



}
