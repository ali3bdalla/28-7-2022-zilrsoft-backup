<?php

namespace Modules\Accounting\Jobs;

use App\Account;
use App\Invoice;
use App\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateSalesEntityTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var TransactionsContainer
     */
    private $entity;
    /**
     * @var Invoice
     */
    private $invoice;

    /**
     * @var array
     */
    private $paymentsMethods;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param array $paymentsMethods
     */
    public function __construct(TransactionsContainer $entity,Invoice $invoice ,$paymentsMethods =  [])
    {
        //
        $this->entity = $entity;
        $this->invoice = $invoice;
        $this->paymentsMethods = $paymentsMethods;
    }

    private function getTotalGatewaysPaidAmount()
    {
        $gatewaysTotalPaidAmount = 0;
        foreach ($this->paymentsMethods as $method){
            if ($method['amount'] > 0){
                $gatewaysTotalPaidAmount = $gatewaysTotalPaidAmount + (float) $method['amount'];
            }
        }
        return $gatewaysTotalPaidAmount;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stockAccount = Account::where('slug','stock')->first();
        $clientAccount = Account::where('slug','clients')->first();
        $clientAccount->debit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'amount' => $this->invoice->moneyFormatter($this->invoice->net),
            'user_id' => $this->invoice->user_id,
            'invoice_id' => $this->invoice->id,
            'container_id' => $this->entity->id,
            'description' => 'client_balance'
        ]);

        $this->addTaxTransactions($stockAccount);


        $gatewaysTotalPaidAmount = $this->getTotalGatewaysPaidAmount();
        if ($gatewaysTotalPaidAmount < $this->invoice->net){
            $amount = (float)$this->invoice->net - (float) $gatewaysTotalPaidAmount;
            if (!$this->invoice->user()->is_system_user){
//                die(1);
                dispatch(new CreateSalesClientBalanceEntityJob($this->entity,$this->invoice,$this->invoice->user(),$amount));
            }else{
                $this->paymentsMethods[0]['amount'] = (float)$this->paymentsMethods[0]['amount'] + (float)$amount;
            }
        }


        if($gatewaysTotalPaidAmount > $this->invoice->net)
        {
            if(count($this->paymentsMethods) == 1)
            {
                $this->paymentsMethods[0]['amount'] = $this->paymentsMethods[0]['amount'] - ((float)$gatewaysTotalPaidAmount - (float)$this->invoice->net);
            }else
            {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    "net"=> ['total paid gateways amount can\'t be more than invoice net'],
                ]);
                throw $error;
            }
        }


        foreach ($this->paymentsMethods as $method){
            if ($method['amount'] > 0){
                $gateway = Account::find($method['id']);
                $gateway->debit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'creditable_id' => $stockAccount->id,
                    'creditable_type' => get_class($stockAccount),
                    'amount' => $this->invoice->moneyFormatter($method['amount']),
                    'user_id' => $this->invoice->user_id,
                    'invoice_id' => $this->invoice->id,
                    'container_id' => $this->entity->id,
                    'description' => 'to_gateway',
                ]);
                $clientAccount->credit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'amount' => $this->invoice->moneyFormatter($method['amount']),
                    'user_id' => $this->invoice->user_id,
                    'invoice_id' => $this->invoice->id,
                    'container_id' => $this->entity->id,
                    'description' => 'client_balance'
                ]);
                dispatch(new CreateSalesPaymentEntityJob($this->invoice,$gateway,$method['amount'],'receipt'));
                $gatewaysTotalPaidAmount = $gatewaysTotalPaidAmount + (float)$method['amount'];
            }
        }
        dispatch(new CreateSalesItemsCostEntityTransactionsJob($this->invoice,$this->entity));
    }


    private function addTaxTransactions(Account $stockAccount)
    {
        $taxAccount = Account::where('slug','vat')->first();
        $gatewayAccounts = auth()->user()->gateways()->where(
            'is_gateway',true
        )->get();
        if (count($gatewayAccounts) === 0){
            $userGatewayAccount  = Account::where([
                ['is_system_account',true],
                ['slug','temp_reseller_account'],
            ])->first();
            $userCashAccountId = $userGatewayAccount->id;
        }else{
            $userGatewayAccount = $gatewayAccounts[0];
            $userCashAccountId = $userGatewayAccount->id;
        }

        $expensesTax = $this->invoice->expenses()->sum('tax');
        $tax = $expensesTax + $this->invoice->tax;
        if ($tax > 0){
            $taxAccount->credit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'debitable_id' => $stockAccount->id,
                'debitable_type' => get_class($stockAccount),
                'amount' =>$this->invoice->moneyFormatter( $tax),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_tax',
            ]);
        }
        $sum = $this->invoice->expenses()->where('with_net',0)->sum('amount');
        if ($sum > 0){
            $taxAccount->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $userCashAccountId,
                'creditable_type' => get_class($userCashAccountId),
                'amount' => $this->invoice->moneyFormatter($sum),
                'user_id' => $this->invoice->user_id,
                'invoice_id' =>  $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_gateway',
            ]);
        }


    }
}
