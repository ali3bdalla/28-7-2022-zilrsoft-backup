<?php

namespace App\Jobs\Accounting\Sale;

use App\Jobs\Items\Accounting\UpdateItemAccountingBalanceJob;
use App\Jobs\User\Balance\UpdateClientBalanceJob;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;

class StoreSaleTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $invoice, $clientAccount, $loggedUser, $startupData, $stockAccount, $taxAccount, $totalPaidAmount, $itemsTaxAmount = 0, $invoiceItems, $itemsCostAmount;
    private $cogsAccount;
    private $productsSalesAccount;
    private $servicesSalesAccount;
    private $otherServicesSalesAccount;
    private $servicesSalesDiscountAccount;
    private $productsSalesDiscountAccount;
    private $otherServicesSalesDiscountAccount;

    /**
     * Create a new job instance.
     *
     * @param Invoice $invoice
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $this->clientAccount = Account::getSystemAccount( 'clients');
        $this->stockAccount = Account::getSystemAccount( 'stock');
        $this->taxAccount = Account::getSystemAccount( 'vat');
        $this->cogsAccount = Account::getSystemAccount( 'cogs');
        $this->productsSalesAccount = Account::getSystemAccount( 'products_sales');
        $this->servicesSalesAccount = Account::getSystemAccount( 'services_sales');
        $this->otherServicesSalesAccount = Account::getSystemAccount( 'other_services_sales');
        $this->productsSalesDiscountAccount = Account::getSystemAccount( 'products_sales_discount');
        $this->servicesSalesDiscountAccount = Account::getSystemAccount( 'services_sales_discount');
        $this->otherServicesSalesDiscountAccount = Account::getSystemAccount( 'other_services_sales_discount');

        $this->loggedUser = auth()->user();
        $this->totalPaidAmount = 0;
        $this->invoiceItems = $invoice->items()->where('is_kit', false)->get();
        $transactionContainer = new TransactionsContainer(
            [
                'creator_id' => $this->loggedUser->id,
                'organization_id' => $this->loggedUser->organization_id,
                'invoice_id' => $invoice->id,
                'amount' => 0,
                'description' => 'Invoice #' . $invoice->id
            ]
        );
        $transactionContainer->save();
        $this->startupData = [
            'organization_id' => $this->invoice->organization_id,
            'creator_id' => $this->loggedUser->id,
            'container_id' => $transactionContainer->id,
            'invoice_id' => $this->invoice->id,
        ];
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->createItemsTransactions();
        $this->createTaxTransaction();
        //client payments should be before payments transactions
        // may update payments amount
        $this->createClientBalanceTransaction();
        $this->createPaymentsTransactions();

        $this->createCogsTransactions();
        $this->createCostTransactions();
    }

    private function createItemsTransactions()
    {

        foreach ($this->invoiceItems as $item) {
            //            if (!$item->item->is_service) {
            $amount = (float)$item->item->cost * (float)$item->qty;
            $data = $this->startupData;
            $data['amount'] = $amount;
            $data['type'] = 'credit';
            $data['item_id'] = $item['item_id'];
            $this->stockAccount->transactions()->create($data);
            dispatch_sync(new UpdateItemAccountingBalanceJob($item->item, $item->subtotal, 'credit'));
            $this->itemsTaxAmount += $item->tax;
            $this->itemsCostAmount += $amount;
            //            }

        }
    }

    private function createTaxTransaction()
    {

        $expensesTax = $this->invoice->expenses()->sum('tax');
        $totalTaxAmount = $expensesTax + $this->itemsTaxAmount;

        if ($totalTaxAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $totalTaxAmount;
            $data['type'] = 'credit';
            $this->taxAccount->transactions()->create($data);
        }

        /**
         * +++++++++++++++++++++++++++++++
         * todo: create expenses tax transaction
         * +++++++++++++++++++++++++++++++
         *
         */
        $sum = $this->invoice->expenses()->where('with_net', 0)->sum('amount');
        if ($sum > 0) {
            $data = $this->startupData;
            $data['type'] = 'debit';
            $data['amount'] = $sum;
            $this->taxAccount->transactions()->create($data);
        }
    }

    private function createClientBalanceTransaction()
    {
        $paidAmount = $this->invoice->payments()->sum('amount');
        $netAmount = (float)$this->invoice->net;
        if ($paidAmount != $netAmount) {
            $variation =  (float)$paidAmount - (float)$netAmount;
            if ($this->invoice->user->is_system_user) {
                $fistPayment = $this->invoice->payments()->first();
                if ($fistPayment == null) {
                    throw ValidationException::withMessages(['payments' => "summation of payments methods should match invoice net "]);
                } else {
                    if ($variation > 0) {
                        $fistPayment->update([
                            'amount' => $fistPayment->amount + (float)$variation
                        ]);
                    } else {
                        if ($variation > 0) {
                            $fistPayment->update([
                                'amount' => $fistPayment->amount - (float)abs($variation)
                            ]);
                        }
                    }
                }
            } else {
                if ($variation > 0) {
                    $data = $this->startupData;
                    $data['amount'] = abs($variation);
                    $data['user_id'] = $this->invoice->user_id;
                    $data['type'] = 'credit';
                    $this->clientAccount->transactions()->create($data);
                    dispatch_sync(new UpdateClientBalanceJob($this->invoice->user, abs($variation), 'decrease'));
                } else {
                    $data = $this->startupData;
                    $data['amount'] = abs($variation);
                    $data['user_id'] = $this->invoice->user_id;
                    $data['type'] = 'debit';
                    $this->clientAccount->transactions()->create($data);
                    dispatch_sync(new UpdateClientBalanceJob($this->invoice->user, abs($variation), 'increase'));
                }
            }
        }
    }

    private function createPaymentsTransactions()
    {
        $data = $this->startupData;
        $data['type'] = 'debit';
        $data['user_id'] = $this->invoice->user_id;
        foreach ($this->invoice->payments()->get() as $key => $payment) {
            $data['amount'] = $payment->amount;
            $payment->account->transactions()->create($data);
        }
    }

    private function createCogsTransactions()
    {
        if ($this->itemsCostAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $this->itemsCostAmount;
            $data['type'] = 'debit';
            $this->cogsAccount->transactions()->create($data);
        }
    }

    public function createCostTransactions()
    {


        $productsSalesTotalAmount = 0;
        $productsSalesTotalDiscountAmount = 0;

        $servicesSalesTotalAmount = 0;
        $servicesSalesTotalDiscountAmount = 0;

        $otherServicesSalesTotalAmount = 0;
        $otherServicesSalesTotalDiscountAmount = 0;


        foreach ($this->invoiceItems as $item) {
            if ($item->item->is_service) {
                $servicesSalesTotalAmount += $item->total;
                $servicesSalesTotalDiscountAmount += $item->discount;
            } elseif ($item->item->is_expense) {
                $otherServicesSalesTotalAmount += $item->total;
                $otherServicesSalesTotalDiscountAmount += $item->discount;
            } else {
                $productsSalesTotalAmount += $item->total;
                $productsSalesTotalDiscountAmount += $item->discount;
            }
        }


        if ($productsSalesTotalAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $productsSalesTotalAmount;
            $data['type'] = 'credit';
            $this->productsSalesAccount->transactions()->create($data);
        }


        if ($servicesSalesTotalAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $servicesSalesTotalAmount;
            $data['type'] = 'credit';
            $this->servicesSalesAccount->transactions()->create($data);
        }


        if ($otherServicesSalesTotalAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $otherServicesSalesTotalAmount;
            $data['type'] = 'credit';
            $this->otherServicesSalesAccount->transactions()->create($data);
        }


        if ($productsSalesTotalDiscountAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $productsSalesTotalDiscountAmount;
            $data['user_id'] = $this->invoice->user_id;
            $data['type'] = 'debit';
            $this->productsSalesDiscountAccount->transactions()->create($data);
        }

        if ($servicesSalesTotalDiscountAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $servicesSalesTotalDiscountAmount;
            $data['type'] = 'debit';
            $this->servicesSalesDiscountAccount->transactions()->create($data);
        }


        if ($otherServicesSalesTotalDiscountAmount > 0) {
            $data = $this->startupData;
            $data['amount'] = $otherServicesSalesTotalDiscountAmount;
            $data['user_id'] = $this->invoice->user_id;
            $data['type'] = 'debit';
            $this->otherServicesSalesDiscountAccount->transactions()->create($data);
        }
    }
}
