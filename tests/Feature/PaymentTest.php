<?php

namespace Tests\Feature;

use App\Gateway;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{

    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        auth()->loginUsingId(1);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testReceipt()
    {

        $data = [
            'user_id' => 1,
            'pay_method' => Gateway::inRandomOrder()->first()->id,
            'amount' => 23.34,
            'receipt_type' => 'balance',
            'invoices' => 'balance',
        ];


        $response = $this->withHeaders(
                [
                    'HTTP_Authorization' => csrf_token(),
                    'X-Requested-With'=>'XMLHttpRequest'
                ]
            )->json('POST','/management/payments/create/store_receipt',$data);


        $response->dump();

        $response->assertOk();
    }
}
