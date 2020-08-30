<?php

namespace Modules\Accounting\Jobs;

use App\Account;
use App\Invoice;
use App\InvoiceItems;
use App\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePurchasesItemsEntityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var InvoiceItems
     */
    private $invoiceItem;
    /**
     * @var int
     */
    private $totalItemExpenses;
    /**
     * @var TransactionsContainer
     */
    private $transactionContainer;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $transactionContainer
     * @param Invoice $invoice
     * @param InvoiceItems $invoiceItem
     * @param int $totalItemExpenses
     */
    public function __construct(TransactionsContainer $transactionContainer,Invoice $invoice,InvoiceItems $invoiceItem,$totalItemExpenses = 0)
    {
        //
        $this->invoice = $invoice;
        $this->invoiceItem = $invoiceItem;
        $this->totalItemExpenses = $totalItemExpenses;
        $this->transactionContainer = $transactionContainer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

//        die($this->transactionContainer);
//        exit();
        $stockAccount = Account::where('slug','stock')->first();
        $this->invoiceItem->item->debit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'container_id' => $this->transactionContainer->id,
            'organization_id' => auth()->user()->organization_id,
            'creditable_id' => $stockAccount->id,
            'creditable_type' => get_class($stockAccount),
            'amount' => $this->invoiceItem->moneyFormatter($this->invoiceItem->subtotal + (float)$this->totalItemExpenses),
            'user_id' => $this->invoiceItem->user_id,
            'invoice_id' => $this->invoiceItem->invoice_id,
            'description' => 'to_item',
        ]);

    }


}
