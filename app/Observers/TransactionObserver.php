<?php

namespace App\Observers;

use App\Events\Transaction\TransactionCreatedEvent;
use App\Events\Transaction\TransactionErasedEvent;
use App\Models\Transaction;

class TransactionObserver
{

    /**
     * Handle the transaction "created" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function created(Transaction $transaction)
    {
        event(new TransactionCreatedEvent($transaction));
    }

    /**
     * Handle the transaction "updated" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function updated(Transaction $transaction)
    {

    }

    /**
     * Handle the transaction "deleted" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function deleted(Transaction $transaction)
    {
        event(new TransactionErasedEvent($transaction));
    }

    /**
     * Handle the transaction "restored" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function restored(Transaction $transaction)
    {
        //
    }

    /**
     * Handle the transaction "force deleted" event.
     *
     * @param Transaction $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction)
    {
        event(new TransactionErasedEvent($transaction));
    }


}
