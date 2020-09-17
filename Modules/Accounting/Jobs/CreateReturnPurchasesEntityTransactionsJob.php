<?php

namespace Modules\Accounting\Jobs;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\TransactionsContainer;
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
                        'creditable_id' => $creatorStock->id,
                        'creditable_type' => get_class($creatorStock),
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

//        $this->toCreateInvoiceTaxTransactions($inc, $creatorStock, $items, $expenses, $container_id);


        if ($totalPaidAmount < $this->invoice->net) {
            $amount = (float)$this->invoice->net - (float)$totalPaidAmount + (float)$expensesAmount;
            $this->invoice->user()->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $creatorStock->id,
                'creditable_type' => get_class($creatorStock),
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
        $gatewayAccounts = auth()->user()->gateways()->where(
            'is_gateway', true
        )->get();
        if (count($gatewayAccounts) === 0) {
            $userGatewayAccount = Account::where([
                ['is_system_account', true],
                ['slug', 'temp_reseller_account'],
            ])->first();
            $userCashAccountId = $userGatewayAccount->id;
        } else {
            $userGatewayAccount = $gatewayAccounts[0];
            $userCashAccountId = $userGatewayAccount->id;
        }

        $expensesTax = $this->invoice->expenses()->sum('tax');
        $tax = $expensesTax + $this->invoice->tax;
        if ($tax > 0) {
            $taxAccount->credit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'debitable_id' => $stockAccount->id,
                'debitable_type' => get_class($stockAccount),
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
                'creditable_id' => $userCashAccountId,
                'creditable_type' => get_class($userCashAccountId),
                'amount' => $this->invoice->moneyFormatter($sum),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_gateway',
            ]);
        }

    }


}
