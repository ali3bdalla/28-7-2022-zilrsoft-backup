<?php

namespace Modules\Sales\Tests\Feature\ReturnSale;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class ReturnSalesWithItemsTest extends TestCase
{

    private $salesInvoice;

    private $itemsCount = 15;
    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function testSuccessCreateSalesWithItemsNotIncludingKitsAndSerials()
    {
        $items = $this->createPurchaseTest();
        self::assertIsArray($items);
        $this->createSaleTest($items);
        $this->createReturnSaleTest($items);
    }


    private function createPurchaseTest()
    {
        $vendor = User::where([
            ['is_vendor', true],
            ['is_system_user', false],
        ])->inRandomOrder()->first();
        $manager = Manager::inRandomOrder()->first();
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $items = Item::
            where('price','>',10)
            ->notExpense()
//            ->hasSerial()
            ->notKit()
            ->take($this->itemsCount)
            ->InRandomOrder()
            ->get();

        $this->assertCount($this->itemsCount, $items);
        $this->assertIsIterable($items);
        $purchaseQueryItems = [];
        foreach ($items as $item) {
            $itemPlaceholder = [];
            $this->assertInstanceOf(Item::class, $item);
            $itemPlaceholder['qty'] = rand(3, 2);
            $itemPlaceholder['discount'] = 0;
            $itemPlaceholder['prinable'] = rand(0, 1);
            $itemPlaceholder['purchase_price'] = $item->last_p_price;
            $serials = [];
            if ($item->isNeedSerial()) {
                for ($i = 0; $i < $itemPlaceholder['qty']; $i++) {
                    $serials[] = (string)Str::uuid();
                }
            }
            $itemPlaceholder['serials'] = $serials;
            $itemPlaceholder['price'] = $item->price;
            $itemPlaceholder['id'] = $item->id;
            $purchaseQueryItems[] = $itemPlaceholder;
        }

        $this->assertIsArray($purchaseQueryItems);
        $data = [
            'items' => $purchaseQueryItems,
            'receiver_id' => $manager->id,
            'vendor_id' => $vendor->id,
            'vendor_invoice_id' => uniqid(),
            'methods' => [$tempResellerAccount->toArray()]
        ];

        $headers = [
            'accept' => 'application/json'
        ];
        $response = $this->post('/purchases', $data, $headers);
        if ($response->status() !== 200) {
            $response->dump();
        }
        $response->assertOk();
        return $purchaseQueryItems;
    }


    public function createSaleTest($salesQueryItems = [])
    {

        $client = User::where([
            ['is_client', true],
            ['is_system_user', false],
        ])->inRandomOrder()->first();
        $manager = Manager::inRandomOrder()->first();

        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;


        $this->assertCount($this->itemsCount, $salesQueryItems);
        $this->assertIsIterable($salesQueryItems);
        $this->assertIsArray($salesQueryItems);
        $data = [
            'items' => $salesQueryItems,
            'salesman_id' => $manager->id,
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
        $this->salesInvoice = Invoice::find(json_decode($response->content(), true)['id']);

    }


    public function createReturnSaleTest($returnSalesQueryItems = [])
    {
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $this->assertCount($this->itemsCount, $returnSalesQueryItems);
        $this->assertIsIterable($returnSalesQueryItems);
        $this->assertIsArray($returnSalesQueryItems);
        $finalQtyItems = [];
        foreach ($this->salesInvoice->items as $key => $item) {
//            dd($returnSalesQueryItems[$key]['serials']);
            $queryItem['id'] = $item['id'];
            $queryItem['serials'] = $returnSalesQueryItems[$key]['serials'];
            $queryItem['returned_qty'] = $item['qty'];
            $finalQtyItems[] = $queryItem;
        }

//        dd($finalQtyItems);
        $data = [
            'items' => $finalQtyItems,
            'methods' => [$tempResellerAccount->toArray()]
        ];

        $headers = [
            'accept' => 'application/json'
        ];
        $response = $this->put('/sales/' . $this->salesInvoice['id'], $data, $headers);
        if ($response->status() !== 200) {
            $response->dump();
        }
        $response->assertOk();

    }

}
