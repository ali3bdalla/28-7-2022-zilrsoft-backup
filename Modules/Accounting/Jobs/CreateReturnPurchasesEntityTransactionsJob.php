<?php

namespace Modules\Accounting\Jobs;

use App\Account;
use App\Invoice;
use App\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Users\Jobs\UpdateUserBalanceJob;

class CreateReturnPurchasesEntityTransactionsJob implements ShouldQueue
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
    private $paymentMethods;
    /**
     * @var array
     */
    private $expenses;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param array $paymentMethods
     * @param array $expenses
     */
    public function __construct(TransactionsContainer $entity, Invoice $invoice, $paymentMethods = [], $expenses = [])
    {

        $this->entity = $entity;
        $this->invoice = $invoice;
        $this->paymentMethods = $paymentMethods;
        $this->expenses = $expenses;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $expensesAmount = $this->getExpenseTotalForNonEmbeddedAmount();
        $creatorStock = auth()->user()->toGetManagerAccount('stock');
        $vendorAccount = auth()->user()->toGetManagerAccount('vendors');
        $totalPaidAmount = 0;
        $vendorAccount->debit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'amount' => $this->invoice->moneyFormatter((float)$this->invoice->net - (float)$expensesAmount),
            'user_id' => $this->invoice->user_id,
            'invoice_id' => $this->invoice->id,
            'container_id' => $this->entity->id,
            'description' => 'vendor_balance'
        ]);


        if ($this->paymentMethods != null) {
            foreach ($this->paymentMethods as $method) {
                if ((float)$method['amount'] > 0) {
                    $gateway = Account::find($method['id']);

                    $gateway->debit_transaction()->create([
                        'creator_id' => auth()->user()->id,
                        'organization_id' => auth()->user()->organization_id,
                        'amount' => $this->invoice->moneyFormatter((float)$method['amount']),
                        'user_id' => $this->invoice->user_id,
                        'invoice_id' => $this->invoice->id,
                        'container_id' => $this->entity->id,
                        'description' => 'to_gateway',
                    ]);

                    $vendorAccount->credit_transaction()->create([
                        'creator_id' => auth()->user()->id,
                        'organization_id' => auth()->user()->organization_id,
                        'amount' => $this->invoice->moneyFormatter((float)$method['amount']),
                        'user_id' => $this->invoice->user_id,
                        'invoice_id' => $this->invoice->id,
                        'container_id' => $this->entity->id,
                        'description' => 'vendor_balance'
                    ]);
                    dispatch(new CreatePurchasesPaymentEntityJob($this->invoice, $gateway, (float)$method['amount'], 'receipt'));
                    $totalPaidAmount = (float)$totalPaidAmount + (float)$method['amount'];
                }


            }
        }

        $this->addTaxTransactions($creatorStock);



        if ($totalPaidAmount < $this->invoice->net) {
            $amount = (float)$this->invoice->net - (float)$totalPaidAmount + (float)$expensesAmount;
            $this->invoice->user()->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'amount' => $this->invoice->moneyFormatter($amount),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_stock',
            ]);
            dispatch(new UpdateUserBalanceJob($this->invoice->user(),'vendor_balance', 'decrease', (float)$amount));
        }

    }

    private function getExpenseTotalForNonEmbeddedAmount()
    {
        $totalAmount = 0;
        foreach (collect($this->expenses) as $expense) {
            if (!$expense["is_apended_to_net"]) {
                $totalAmount += (float)$expense->get('amount');
            }
        }
        return (float)$totalAmount;
    }

    private function addTaxTransactions(Account $stockAccount)
    {

        $taxAccount = Account::where('slug', 'vat')->first();
       
        $expensesTax = $this->invoice->expenses()->sum('tax');
        $tax = $expensesTax + $this->invoice->tax;
        if ($tax > 0) {
            $taxAccount->credit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'amount' => $this->invoice->moneyFormatter($tax),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_tax',
            ]);
        }
        $sum = $this->invoice->expenses()->where('with_net', 0)->sum('amount');
        if ($sum > 0) {
            $taxAccount->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'amount' => $this->invoice->moneyFormatter($sum),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_gateway',
            ]);
        }

    }


}
