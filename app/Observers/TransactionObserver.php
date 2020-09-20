<?php

namespace App\Observers;

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
        $account = $transaction->account;
        if ($transaction->type == 'credit') {
            $account->update([
                'total_credit_amount' => $account->total_credit_amount + (float) $transaction->amount,
            ]);
        } else {
            $account->update([
                'total_debit_amount' => $account->total_debit_amount + (float) $transaction->amount,
            ]);
        }
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
        // event(new TransactionErasedEvent($transaction));
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
        // $account = $transaction->account;
        // if ($transaction->type == 'credit') {

        //     $account->update([
        //         'total_credit_amount' => $account->total_credit_amount - (float) $transaction->amount,
        //     ]);
        // } else {
        //     $account->update([
        //         'total_debit_amount' => $account->total_debit_amount - (float) $transaction->amount,
        //     ]);
        // }
    }

}
