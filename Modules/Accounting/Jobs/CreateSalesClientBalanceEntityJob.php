<?php

namespace Modules\Accounting\Jobs;

use App\Account;
use App\Invoice;
use App\TransactionsContainer;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Users\Jobs\UpdateUserBalanceJob;

class CreateSalesClientBalanceEntityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Invoice
     */
    private $invoice;
    /**
     * @var User
     */
    private $client;
    /**
     * @var int
     */
    private $amount;
    /**
     * @var TransactionsContainer
     */
    private $entity;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $entity
     * @param Invoice $invoice
     * @param User $client
     * @param int $amount
     */
    public function __construct(TransactionsContainer $entity,Invoice $invoice,User $client,$amount = 0)
    {
        //
        $this->invoice = $invoice;
        $this->client = $client;
        $this->amount = $amount;
        $this->entity = $entity;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $stockAccount = Account::where('slug','stock')->first();
        $this->client->debit_transaction()->create([
            'creator_id' => auth()->user()->id,
            'organization_id' => auth()->user()->organization_id,
            'creditable_id' => $stockAccount->id,
            'creditable_type' => get_class($stockAccount),
            'amount' => $this->invoice->moneyFormatter($this->amount),
            'user_id' => $this->client->id,
            'invoice_id' => $this->invoice->id,
            'container_id' => $this->entity->id,
            'description' => 'to_stock',
        ]);
        dispatch(new UpdateUserBalanceJob($this->client,'client_balance','increase',$this->amount));
    }
}
