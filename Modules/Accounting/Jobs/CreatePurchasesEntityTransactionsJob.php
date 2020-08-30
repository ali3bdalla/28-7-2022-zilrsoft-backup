<?php

namespace Modules\Accounting\Jobs;

use App\Account;
use App\Invoice;
use App\Item;
use App\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Users\Jobs\UpdateUserBalanceJob;

class CreatePurchasesEntityTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var array
     */
    private $paymentsMethods;
    /**
     * @var array
     */
    private $expenses;
    private $entity;
    /**
     * @var array
     */
    private $itemRequestData;
    /**
     * @var TransactionsContainer
     */
    private $transactionsContainer;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $transactionsContainer
     * @param Invoice $invoice
     * @param array $paymentsMethods
     * @param array $expenses
     * @param array $itemRequestData
     */
    public function __construct(TransactionsContainer $transactionsContainer,Invoice $invoice, $paymentsMethods = [], $expenses = [], $itemRequestData = [])
    {
        //
        $this->invoice = $invoice;
        $this->paymentsMethods = $paymentsMethods;
        $this->expenses = $expenses;
        $this->itemRequestData = $itemRequestData;
        $this->transactionsContainer = $transactionsContainer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
//
        $this->entity = $this->transactionsContainer;
        $nonEmbeddedExpenseAmount = $this->expensesTotal();
        $creatorStock = Account::where('slug', 'stock')->first();
        $vendorAccount = Account::where('slug', 'vendors')->first();
        $vendorAccount->credit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'amount' => $this->invoice->moneyFormatter((float)$this->invoice->net - (float)$nonEmbeddedExpenseAmount),
            'user_id' => $this->invoice->user_id,
            'invoice_id' => $this->invoice->id,
            'container_id' => $this->entity->id,
            'description' => 'vendor_balance'
        ]);


        $totalGatewayPaidAmount = 0;

        // dd($this->paymentsMethods);
        if ($this->paymentsMethods != null) {

            foreach ($this->paymentsMethods as $method) {
                if ((float)$method['amount'] > 0) {
                    $gateway = Account::find($method['id']);
                    $gateway->credit_transaction()->create([
                        'creator_id' => auth()->user()->id,
                        'organization_id' => auth()->user()->organization_id,
                        'debitable_id' => $creatorStock->id,
                        'debitable_type' => get_class($creatorStock),
                        'amount' => $method['amount'],
                        'user_id' => $this->invoice->user_id,
                        'invoice_id' => $this->invoice->id,
                        'container_id' => $this->entity->id,
                        'description' => 'to_stock',
                    ]);
                    $vendorAccount->debit_transaction()->create([
                        'creator_id' => auth()->user()->id,
                        'organization_id' => auth()->user()->organization_id,
                        'amount' => $this->invoice->moneyFormatter((float)$method['amount']),
                        'user_id' => $this->invoice->user_id,
                        'invoice_id' => $this->invoice->id,
                        'container_id' => $this->entity->id,
                        'description' => 'vendor_balance'
                    ]);

                    dispatch(new CreatePurchasesPaymentEntityJob($this->invoice, $gateway, (float)$method['amount'], 'payment'));
                    $totalGatewayPaidAmount += (float)$method['amount'];
                }
            }
        }



        $this->addTaxTransactions();


        if ($totalGatewayPaidAmount < $this->invoice->net) {
            $amount = (float)$this->invoice->net - ((float)$totalGatewayPaidAmount + (float)$nonEmbeddedExpenseAmount);
            $stockAccount = Account::where('slug','stock')->first();
            $this->invoice->user()->credit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'debitable_id' => $stockAccount->id,
                'debitable_type' => get_class($stockAccount),
                'amount' => $this->invoice->moneyFormatter($amount),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_stock',
            ]);

            dispatch(new UpdateUserBalanceJob($this->invoice->user(),'vendor_balance','increase',$amount));
        }


    }



    public function expensesTotal()
    {
        $totalAmount = 0;
        foreach ($this->expenses as $expense) {
            if (!$expense["is_apended_to_net"]) {
                $totalAmount += (float)$expense["amount"];
            }
        }
        return $totalAmount;
    }

    public function addTaxTransactions()
    {
        $taxAccount = Account::where('slug', 'vat')->first();
        $creatorStock = Account::where('slug', 'stock')->first();
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
        $this->createInvoiceExpense();
        $expensesTax = $this->invoice->expenses()->sum('tax');
        // die()
        $tax = $expensesTax + $this->invoice->tax;
        if ($tax > 0) {
            $taxAccount->debit_transaction()->create([
                'creator_id' => auth()->user()->id,
                'organization_id' => auth()->user()->organization_id,
                'creditable_id' => $creatorStock->id,
                'creditable_type' => get_class($creatorStock),
                'amount' => $this->invoice->moneyFormatter($tax),
                'user_id' => $this->invoice->user_id,
                'invoice_id' => $this->invoice->id,
                'container_id' => $this->entity->id,
                'description' => 'to_tax',
            ]);
        }
        $sum = $this->invoice->expenses()->where('with_net', 0)->sum('amount');
        if ($sum > 0) {
            $cashAccount = $this->invoice->transactions()->where([['creditable_type', 'App\Account'], ['creditable_id', $userCashAccountId]])->first();
            if (!empty($cashAccount)) {
                $newAmount = $cashAccount->amount + $sum;
                $cashAccount->update([
                    'amount' => $newAmount
                ]);
            } else {
                $taxAccount->debit_transaction()->create([
                    'creator_id' => auth()->user()->id,
                    'organization_id' => auth()->user()->organization_id,
                    'creditable_id' => $userGatewayAccount->id,
                    'creditable_type' => get_class($userGatewayAccount),
                    'amount' => $this->invoice->moneyFormatter($sum),
                    'user_id' => $this->invoice->user_id,
                    'invoice_id' => $this->invoice->id,
                    'container_id' => $this->entity->id,
                    'description' => 'to_gateway',
                ]);
            }

        }


    }


    public function createInvoiceExpense()
    {
        $totalTaxesAmount = 0;
        if ($this->expenses != null) {
            foreach ($this->expenses as $expense) {
                foreach ($this->itemRequestData as $item) {
                    $dbItem = Item::findOrFail($item['id']);
                    if( collect($item)->get('widget') == null)
                    {
                        $itemWidget = (float)$this->invoice->total / ((float)$item['purchase_price'] * (int)$item['qty']) ;
                    }else
                    {
                        $itemWidget = $item['widget'];
                    }

                    $amount = (float)$expense['amount'] * (float)$itemWidget  / (float)(1 + $dbItem->vtp / 100); //

                    // die($amount );
                    $tax = (float)$expense['amount'] - (float)$amount;
                    $totalTaxesAmount = (float)$totalTaxesAmount + (float)$tax;
                    // die($totalTaxesAmount);
                }


                $org_vat = auth()->user()->organization->organization_vat;
                // die($org_vat);
                $expenseTax = (float)$expense['amount'] * (float)$org_vat / (float)(100 + $org_vat);
                $this->invoice->expenses()->create(
                    [
                        'expense_id' => $expense['id'],
                        'amount' => $expense['amount'],
                        'tax' => $expenseTax,
                        'with_net' => $expense['is_apended_to_net'],
                    ]
                );
            }
        }

        return $totalTaxesAmount;
    }
}
