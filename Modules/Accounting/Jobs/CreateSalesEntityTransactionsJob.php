<?php

namespace Modules\Accounting\Jobs;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\TransactionsContainer;
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
        $this->handleUnPaidAmountForSystemClient();
        $this->createPaymentsTransactions();
        $this->handleUnPaidAmountForNormalClient();

        dispatch(new CreateSalesItemsCostEntityTransactionsJob($this->invoice, $this->entity));
    }


    private function handleUnPaidAmountForNormalClient()
    {
        if (!$this->invoice->user()->is_system_user) {
            $paidAmount = $this->invoice->payments()->sum('amount');
            $unPaidAmout =  $this->invoice->net - $paidAmount;
            if ($unPaidAmout) {
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
                }
            }
        }
    }
    private function handleUnPaidAmountForSystemClient()
    {
        if ($this->invoice->user()->is_system_user) {
            $paidAmount = $this->invoice->payments()->sum('amount');
            $unPaidAmout =  $this->invoice->net - $paidAmount;
            if ($unPaidAmout) {
                $payments  = $this->invoice->payments()->count();
                if ($payments) {
                    $firstPaymentMethod =  $this->invoice->payments()->first();
                    $firstPaymentMethod->update([
                        'amount' => (float)$unPaidAmout + (float)$firstPaymentMethod->amount
                    ]);
                } else {
                    $error = \Illuminate\Validation\ValidationException::withMessages([
                        "user" => ['system user can\'t  make credit invoices ',],
                    ]);
                    throw $error;
                }
            }
        }
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
