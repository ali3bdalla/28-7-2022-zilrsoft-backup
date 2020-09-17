<?php

namespace Modules\Sales\Tests\Feature\Sale;

use App\Models\Account;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Tests\TestCase;

class ItemsSaleTest extends TestCase
{


    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function testSuccessCreateSalesWithItemsNotIncludingKitsAndSerials()
    {
        auth()->loginUsingId(1);
        $client = User::where('is_client', true)->inRandomOrder()->first();
        $salesman = Manager::inRandomOrder()->first();
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $items = Item::InRandomOrder()->where('available_qty', '>', 4)->notExpense()->notKit()->notHasSerial()->take(5)->get();
        $this->assertCount(5, $items);
        $this->assertIsIterable($items);
        $queryItems = [];
        foreach ($items as $item) {
            $this->assertInstanceOf(Item::class, $item);
            $this->assertGreaterThanOrEqual(5, $item->available_qty);
            $item->qty = rand(1, $item->available_qty);
            $item->discount = rand(0, 2);
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
//        if ($response->status() !== 200) {
        $response->dump();
//        }
        $response->assertOk();
    }
    /**
     * A basic unit test example.
     * @test
     * @return void
     */
//    public function testFailCreateSalesWithItemsNotIncludingKitsAndSerials()
//    {
//        $client = User::where('is_client', true)->inRandomOrder()->first();
//        $salesman = Manager::inRandomOrder()->first();
//        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
//        $tempResellerAccount->amount = 0;
//        $items = Item::InRandomOrder()->where('available_qty', '=', 0)->notExpense()->notKit()->notHasSerial()->take(5)->get();
//        $this->assertCount(5, $items);
//        $this->assertIsIterable($items);
//        $queryItems = [];
//        foreach ($items as $item) {
//            $this->assertInstanceOf(Item::class, $item);
//            $this->assertGreaterThanOrEqual(0, $item->available_qty);
//            $item->qty = rand(1, $item->available_qty);
//            $item->discount = rand(0, 2);
//            $item->prinable = rand(0, 1);
//            $queryItems[] = $item->toArray();
//        }
//        $this->assertIsArray($queryItems);
//        $data = [
//            'items' => $queryItems,
//            'salesman_id' => $salesman->id,
//            'client_id' => $client->id,
//            'methods' => [$tempResellerAccount->toArray()]
//        ];
//
//        $headers = [
//            'accept' => 'application/json'
//        ];
//        $response = $this->post('/sales', $data, $headers);
//        if ($response->status() !== 422) {
//            $response->dump();
//        }
//        $response->assertStatus(422);
//    }


}
