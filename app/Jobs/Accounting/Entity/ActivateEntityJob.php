<?php

namespace App\Jobs\Accounting\Entity;

use App\Models\TransactionsContainer;
use App\Scopes\PendingScope;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivateEntityJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private TransactionsContainer $transactionsContainer;

    /**
     * Create a new job instance.
     */
    public function __construct(TransactionsContainer $transactionsContainer)
    {
        $this->transactionsContainer = $transactionsContainer;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $this->transactionsContainer->transactions()->withoutGlobalScope(PendingScope::class)->update([
            'is_pending' => false,
        ]);
        $this->transactionsContainer->update([
            'is_pending' => false,
        ]);
        $transactions = $this->transactionsContainer->fresh()->transactions()->get();
        foreach ($transactions as $transaction) {
            dispatch_sync(new UpdateAccountBalanceJob($transaction));
        }
    }
}
