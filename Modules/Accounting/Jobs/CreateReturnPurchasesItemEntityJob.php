<?php

namespace Modules\Accounting\Jobs;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateReturnPurchasesItemEntityJob implements ShouldQueue
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
     * @var InvoiceItems
     */
    private $invoiceItem;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param InvoiceItems $invoiceItem
     */
    public function __construct(TransactionsContainer $entity, Invoice $invoice, InvoiceItems $invoiceItem)
    {
        //
        $this->entity = $entity;
        $this->invoice = $invoice;
        $this->invoiceItem = $invoiceItem;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->invoiceItem->item->credit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'amount' => $this->invoiceItem->moneyFormatter($this->invoiceItem->subtotal),
            'user_id' => $this->invoice->user_id,
            'invoice_id' => $this->invoiceItem->invoice_id,
            'description' => 'to_item',
        ]);


    }
}
