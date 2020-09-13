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
use Modules\Users\Jobs\UpdateUserBalanceJob;

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




    private $taxAccount;
    private $stockAccount;
    private $clientsAccount;
    private $loggedUser;



    private $totalTaxAmount = 0;
    /**
     * @var array
     */
    // private $paymentsMethods;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param array $paymentsMethods
     */
    public function __construct(TransactionsContainer $entity, Invoice $invoice)
    {
        //
        $this->entity = $entity;
        $this->invoice = $invoice;
        $this->taxAccount = Account::where('slug', 'vat')->first();
        $this->stockAccount = Account::where('slug', 'stock')->first();
        $this->clientsAccount = Account::where('slug', 'clients')->first();
        $this->loggedUser = auth()->user();
    }

    private function getTotalGatewaysPaidAmount()
    {
        $gatewaysTotalPaidAmount = 0;
        if ($this->paymentsMethods != null) {
            foreach ($this->paymentsMethods as $method) {
                if ($method['amount'] > 0) {
                    $gatewaysTotalPaidAmount = $gatewaysTotalPaidAmount + (float) $method['amount'];
                }
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
        $this->createItemsTransactions();
        $this->createTaxesTransactions();
        $this->createPaymentsTransactions();
        dispatch(new CreateSalesItemsCostEntityTransactionsJob($this->invoice, $this->entity));
        $paidAmount = $this->invoice->payments()->sum('amount');
        if ($paidAmount < $this->invoice->net) {
            if (!$this->invoice->user()->is_system_user) {
                $amount = (float)$this->invoice->net - (float)$paidAmount;
                $this->clientsAccount->debit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'amount' => $amount,
                    'user_id' => $this->invoice->user_id,
                    'invoice_id' => $this->invoice->id,
                    'container_id' => $this->entity->id,
                    'description' => 'to_stock',
                ]);
                dispatch(new UpdateUserBalanceJob($this->invoice->user(), 'client_balance', 'increase', $amount));
            } else {
                $error = \Illuminate\Validation\ValidationException::withMessages([
                    "user" => ['system user can\'t  make credit invoices '],
                ]);
                throw $error;
            }
        }


        // $stockAccount = Account::where('slug','stock')->first();
        // $clientAccount = Account::where('slug','clients')->first();

        // $clientAccount->debit_transaction()->create([
        //     'creator_id' => auth()->user()->id,
        //     'organization_id' => auth()->user()->organization_id,
        //     'amount' => $this->invoice->moneyFormatter($this->invoice->net),
        //     'user_id' => $this->invoice->user_id,
        //     'invoice_id' => $this->invoice->id,
        //     'container_id' => $this->entity->id,
        //     'description' => 'client_balance'
        // ]);


        // die($this->invoice->moneyFormatter(collect($this->paymentsMethods)->sum('amount')) . ' ' .(float)$this->invoice->net);
        // $this->addTaxTransactions($stockAccount);
        // if($this->paymentsMethods != null)
        // {
        //     foreach ($this->paymentsMethods as $method){
        //         if ((float)$method['amount'] > 0){
        //             $gateway = Account::find($method['id']);
        //             $gateway->debit_transaction()->create([
        //                 'creator_id' => auth()->user()->id,
        //                 'organization_id' => auth()->user()->organization_id,
        //                 // 'creditable_id' => $stockAccount->id,
        //                 // 'creditable_type' => get_class($stockAccount),
        //                 'amount' => $this->invoice->moneyFormatter($method['amount']),
        //                 'user_id' => $this->invoice->user_id,
        //                 'invoice_id' => $this->invoice->id,
        //                 'container_id' => $this->entity->id,
        //                 'description' => 'to_gateway',
        //             ]);
        //             $clientAccount->credit_transaction()->create([
        //                 'creator_id' => auth()->user()->id,
        //                 'organization_id' => auth()->user()->organization_id,
        //                 'amount' => $this->invoice->moneyFormatter((float)$method['amount']),
        //                 'user_id' => $this->invoice->user_id,
        //                 'invoice_id' => $this->invoice->id,
        //                 'container_id' => $this->entity->id,
        //                 'description' => 'client_balance'
        //             ]);
        //             //
        //             dispatch(new CreateSalesPaymentEntityJob($this->invoice,$gateway,$method['amount'],'receipt'));
        //             // $gatewaysTotalPaidAmount = $gatewaysTotalPaidAmount + (float)$method['amount'];
        //         }
        //     }
        // }




        // $gatewaysTotalPaidAmount = $this->getTotalGatewaysPaidAmount();
        // if ($gatewaysTotalPaidAmount < $this->invoice->net){
        //     $amount = (float)$this->invoice->net - (float) $gatewaysTotalPaidAmount;
        //     if (!$this->invoice->user()->is_system_user){
        //         $this->invoice->user()->debit_transaction()->create([
        //             'creator_id' => auth()->user()->id,
        //             'organization_id' => auth()->user()->organization_id,
        //             // 'creditable_id' => $stockAccount->id,
        //             // 'creditable_type' => get_class($stockAccount),
        //             'amount' => $this->invoice->moneyFormatter($amount),
        //             'user_id' => $this->invoice->user_id,
        //             'invoice_id' => $this->invoice->id,
        //             'container_id' => $this->entity->id,
        //             'description' => 'to_stock',
        //         ]);
        //         dispatch(new UpdateUserBalanceJob($this->invoice->user(),'client_balance','increase',$amount));
        //     }else{
        //         if($this->paymentsMethods != null && count($this->paymentsMethods) == 1)
        //         {
        //             $this->paymentsMethods[0]['amount'] = (float)$this->paymentsMethods[0]['amount'] + (float)$amount;
        //         }else
        //         {
        //             $error = \Illuminate\Validation\ValidationException::withMessages([
        //                 "net"=> ['total paid gateways amount can\'t be more than invoice net'],
        //             ]);
        //             throw $error;
        //         }
        //     }
        // }


        // if($gatewaysTotalPaidAmount > $this->invoice->net)
        // {
        //     if($this->paymentsMethods != null && count($this->paymentsMethods) == 1)
        //     {
        //         $this->paymentsMethods[0]['amount'] = $this->paymentsMethods[0]['amount'] - ((float)$gatewaysTotalPaidAmount - (float)$this->invoice->net);
        //     }else
        //     {
        //         $error = \Illuminate\Validation\ValidationException::withMessages([
        //             "net"=> ['total paid gateways amount can\'t be more than invoice net'],
        //         ]);
        //         throw $error;
        //     }
        // }

    }



    private function createItemsTransactions()
    {
        $items = $this->invoice->items()->where([[
            'is_kit', false
        ]])->get();
        foreach ($items as $invoiceItem) {
            if (!$invoiceItem->item->isService()) {
                $amount = (float)$invoiceItem->item->cost * (int)$invoiceItem->qty;
                $invoiceItem->item->credit_transaction()->create([
                    'creator_id' => $this->loggedUser->id,
                    'organization_id' => $this->loggedUser->organization_id,
                    'container_id' => $this->entity->id,
                    'amount' => $amount,
                    'user_id' => $invoiceItem->user_id,
                    'invoice_id' => $this->invoice->id,
                    'description' => 'to_item',
                ]);
                $this->totalTaxAmount += $invoiceItem->tax;
            }
        }
    }


    private function createTaxesTransactions()
    {

        $expensesTax = $this->invoice->expenses()->sum('tax');
        $tax = $expensesTax + $this->totalTaxAmount;
        if ($tax > 0) {
            $this->taxAccount->credit_transaction()->create([
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'amount' => $tax,
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_tax',
            ]);
        }

        $sum = $this->invoice->expenses()->where('with_net', 0)->sum('amount');
        if ($sum > 0) {
            $this->taxAccount->debit_transaction()->create([
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'amount' => $sum,
                'user_id' => $this->invoice->user_id,
                'invoice_id' =>  $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_gateway',
            ]);
        }
    }


    public function createPaymentsTransactions()
    {
        foreach ($this->invoice->payments as $key => $payment) {
            $payment->paymentable->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'amount' => $payment->amount,
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_gateway',
            ]);
        }
    }
}
