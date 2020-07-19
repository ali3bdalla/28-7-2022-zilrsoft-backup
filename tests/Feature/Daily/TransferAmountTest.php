<?php

namespace Tests\Feature\Daily;

use App\Account;
use Tests\TestCase;
use App\Http\Middleware\VerifyCsrfToken;
use App\Manager;

class TransferAmountTest extends TestCase
{
    /**
     * A basic feature test example.
     *  @test
     * @return void
     */
    public function toConfirmTransferFromManagerWalletToAnother()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $sender = Manager::inRandomOrder()->first();
        $senderGateway = $sender->gateways()->first();
        while($senderGateway==null || $senderGateway->current_amount <= 0)
        {
            $sender = Manager::inRandomOrder()->first();
            $senderGateway = $sender->gateways()->first();
        }
        $this->assertInstanceOf(Manager::class,$sender);
        $this->assertInstanceOf(Account::class,$senderGateway);

        $amountToTransfer  = $senderGateway->current_amount / 2;
        $this->assertIsNumeric($amountToTransfer );
        $receiver = Manager::where('id','!=',$sender->id)->inRandomOrder()->first();

        $receiverGateway = $sender->gateways()->first();
        
        while($receiverGateway==null)
        {
            $receiver = Manager::where('id','!=',$sender->id)->inRandomOrder()->first();
            $receiverGateway = $sender->gateways()->first();
        }
        
        $this->assertInstanceOf(Manager::class,$receiver);
        $this->assertInstanceOf(Account::class,$receiverGateway);
    
        $receiverGatewayAmount = $receiverGateway->current_amount;
        $this->assertIsNumeric($receiverGatewayAmount);

        auth()->loginUsingId($sender->id);
        $response = $this->post(route('accounting.reseller_daily.transfer_amounts_store'),[
            'gateway_id' => $senderGateway->id,
            'receiver_id' => $receiver->id,
            'receiver_gateway_id' => $receiverGateway->id,
            'amount' => $amountToTransfer 
        ],[
            'accept'=> "Application/json"
        ]);
       
        $response->assertStatus(201);
        $transaction_id =  json_decode($response->getContent(),true)['id'];
        $this->assertIsNumeric($transaction_id);
        $confirmResponse = $this->get(route('accounting.reseller_daily.confirm_transaction',$transaction_id),[
            'accept'=> "Application/json"
        ]);
        $confirmResponse->assertStatus(302);
        $this->assertEquals($receiverGateway->fresh()->current_amount,$receiverGatewayAmount + $amountToTransfer );
            


    }


     /**
     * A basic feature test example.
     *  @test
     * @return void
     */

    public function toRejectTransferAmountFromWalletToAnother()
    {
        $this->withoutMiddleware(VerifyCsrfToken::class);
        $sender = Manager::inRandomOrder()->first();
        $senderGateway = $sender->gateways()->first();
        while($senderGateway==null || $senderGateway->current_amount <= 0)
        {
            $sender = Manager::inRandomOrder()->first();
            $senderGateway = $sender->gateways()->first();
        }
        $this->assertInstanceOf(Manager::class,$sender);
        $this->assertInstanceOf(Account::class,$senderGateway);

        $amountToTransfer  = $senderGateway->current_amount / 2;
        $this->assertIsNumeric($amountToTransfer );
        $receiver = Manager::where('id','!=',$sender->id)->inRandomOrder()->first();

        $receiverGateway = $sender->gateways()->first();
        
        while($receiverGateway==null)
        {
            $receiver = Manager::where('id','!=',$sender->id)->inRandomOrder()->first();
            $receiverGateway = $sender->gateways()->first();
        }
        $this->assertInstanceOf(Manager::class,$receiver);
        $this->assertInstanceOf(Account::class,$receiverGateway);
        $senderGatewayAmount = $senderGateway->current_amount;
        $this->assertIsNumeric($senderGatewayAmount);

        auth()->loginUsingId($sender->id);
        $response = $this->post(route('accounting.reseller_daily.transfer_amounts_store'),[
            'gateway_id' => $senderGateway->id,
            'receiver_id' => $receiver->id,
            'receiver_gateway_id' => $receiverGateway->id,
            'amount' => $amountToTransfer 
        ],[
            'accept'=> "Application/json"
        ]);

        $this->assertLessThanOrEqual($senderGateway->fresh()->current_amount,$senderGatewayAmount);
        $response->assertStatus(201);
        $transactionId =  json_decode($response->getContent(),true)['id'];
        $this->assertIsNumeric($transactionId);
    
        $confirmResponse = $this->get(route('accounting.reseller_daily.delete_transaction',$transactionId),[
            'accept'=> "Application/json"
        ]);
        $confirmResponse->assertStatus(302);
        $this->assertEquals($senderGateway->fresh()->current_amount,$senderGatewayAmount);
        
    }
}
