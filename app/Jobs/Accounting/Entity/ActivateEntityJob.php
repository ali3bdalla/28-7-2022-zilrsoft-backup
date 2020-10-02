<?php

namespace App\Jobs\Accounting\Entity;

use App\Http\Resources\Entity\TransactionCollection;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivateEntityJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $transactionsContainer;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(TransactionsContainer $transactionsContainer)
    {
        //
        $this->transactionsContainer = $transactionsContainer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->transactionsContainer->transactions()->withoutGlobalScope('pendingTransactionScope')->update([
            'is_pending' => false
        ]);
        $this->transactionsContainer->update([
            'is_pending' => false
        ]);//


        $transactions = $this->transactionsContainer->fresh()->transactions()->get();

        foreach( $transactions as $transaction)
        {
            dispatch(new UpdateAccountBalanceJob($transaction));
        }
    }
}
