<?php

namespace Modules\Sales\Tests\Feature\Sale;

use App\Account;
use App\Item;
use App\Manager;
use App\User;
use Tests\TestCase;

class SaleswithExpenseOnlyTest extends TestCase
{


    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function testSuccessCreateSalesWithItemsIncludingExpenseOnly()
    {
        $client = User::where('is_client', true)->inRandomOrder()->first();
        $salesman = Manager::inRandomOrder()->first();
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $items = Item::InRandomOrder()->expense()->notKit()->notHasSerial()->take(3)->get();
        $this->assertIsIterable($items);
        $queryItems = [];
        foreach ($items as $item) {
            $this->assertInstanceOf(Item::class, $item);
            $this->assertGreaterThanOrEqual(0, $item->available_qty);
            $item->qty = rand(1, $item->available_qty);
            $item->discount = 0;
            $item->prinable = rand(0, 1);
            $item->purchase_price = rand(0, $item->price);
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


    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function testFailCreateSalesWithItemsIncludingExpenseOnly()
    {
        $client = User::where('is_client', true)->inRandomOrder()->first();
        $salesman = Manager::inRandomOrder()->first();
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $items = Item::InRandomOrder()->expense()->notKit()->notHasSerial()->take(3)->get();
        $this->assertIsIterable($items);
        $queryItems = [];
        foreach ($items as $item) {
            $this->assertInstanceOf(Item::class, $item);
            $this->assertGreaterThanOrEqual(0, $item->available_qty);
            $item->qty = rand(1, $item->available_qty);
            $item->discount = 0;
            $item->prinable = rand(0, 1);
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
        if ($response->status() !== 422) {
            $response->dump();
        }
        $response->assertStatus(422);
    }


}
