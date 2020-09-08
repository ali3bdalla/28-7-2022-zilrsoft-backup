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

class CreateSalesItemEntity implements ShouldQueue
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
    public function __construct(TransactionsContainer $entity,Invoice $invoice,InvoiceItems $invoiceItem)
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
        // $stockAccount = Account::where('slug','stock')->first();
        $amount = $this->invoiceItem->moneyFormatter((float)$this->invoiceItem->item->cost * $this->invoiceItem->qty);
//        echo $amount;
//        exit();
        $this->invoiceItem->item->credit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'container_id' => $this->entity->id,
            // 'debitable_id' => $stockAccount->id,
            // 'debitable_type' => get_class($stockAccount),
            'amount' => $amount,
            'user_id' => $this->invoiceItem->user_id,
            'invoice_id' => $this->invoiceItem->invoice_id,
            'description' => 'to_item',
        ]);


       
    }
}
