<?php

namespace Modules\Purchases\Tests\Feature\ReturnPurchase;

use App\Account;
use App\Invoice;
use App\Item;
use App\Manager;
use App\User;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateReturnPurchaseTest extends TestCase
{

    private $purchaseInvoice;

    private $itemsCount = 15;
    /**
     * A basic unit test example.
     * @test
     * @return void
     */
    public function testPreformReturnPurchaseInvoice()
    {
        auth()->loginUsingId(1);
        $items = $this->createPurchaseTest();
        self::assertIsArray($items);
        $this->createReturnPurchaseTest($items);
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
            ->notKit()
            ->hasSerial()
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
            'vendor_inc_number' => uniqid(),
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
        $this->purchaseInvoice = Invoice::find(json_decode($response->content(), true)['id']);

        return $purchaseQueryItems;
    }




    public function createReturnPurchaseTest($returnPurchaseQueryItems = [])
    {
        $tempResellerAccount = Account::where([['slug', 'temp_reseller_account'], ['is_system_account', true]])->first();
        $tempResellerAccount->amount = 0;
        $this->assertCount($this->itemsCount, $returnPurchaseQueryItems);
        $this->assertIsIterable($returnPurchaseQueryItems);
        $this->assertIsArray($returnPurchaseQueryItems);
        $finalQtyItems = [];
        foreach ($this->purchaseInvoice->items as $key => $item) {
            $queryItem['id'] = $item['id'];
            $queryItem['serials'] = $returnPurchaseQueryItems[$key]['serials'];
            $queryItem['returned_qty'] = $item['qty'];
            $finalQtyItems[] = $queryItem;
        }

        $data = [
            'items' => $finalQtyItems,
            'methods' => [$tempResellerAccount->toArray()]
        ];

        $headers = [
            'accept' => 'application/json'
        ];
        $response = $this->put('/purchases/' . $this->purchaseInvoice['id'], $data, $headers);
        if ($response->status() !== 200) {
            $response->dump();
        }
        $response->assertOk();

    }

}
