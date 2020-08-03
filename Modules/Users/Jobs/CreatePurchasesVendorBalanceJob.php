<?php

namespace Modules\Users\Jobs;

use App\Account;
use App\Invoice;
use App\TransactionsContainer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreatePurchasesVendorBalanceJob implements ShouldQueue
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
     * @var User
     */
    private $vendor;
    /**
     * @var int
     */
    private $amount;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param User $vendor
     * @param int $amount
     */
    public function __construct(TransactionsContainer $entity,Invoice $invoice,User $vendor,$amount = 0)
    {
        //
        $this->entity = $entity;
        $this->invoice = $invoice;
        $this->vendor = $vendor;
        $this->amount = $amount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stockAccount = Account::where('slug','stock')->first();

        $this->vendor->credit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'debitable_id' => $stockAccount->id,
            'debitable_type' => get_class($stockAccount),
            'amount' => $this->invoice->moneyFormatter($this->amount),
            'user_id' => $this->invoice->user_id,
            'invoice_id' => $this->invoice->id,
            'container_id' => $this->entity->id,
            'description' => 'to_stock',
        ]);
    }
}
