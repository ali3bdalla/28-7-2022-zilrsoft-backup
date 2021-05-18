<?php

namespace App\Observers;

use App\Models\Account;

class AccountObserver
{

    /**
     * Handle the organization "created" event.
     *
     * @param Account $account
     * @return void
     */
    public function created(Account $account)
    {

        if ($account->parent) {
            $account->parent->updateHashMap();
        }
        $account->updateHashMap();
        $account->updateSerial();
    }

    /**
     * Handle the organization "updated" event.
     *
     * @param Account $account
     * @return void
     */
    public function updated(Account $account)
    {
        if ($account->parent) {
            $account->parent->updateHashMap();
        }

        $account->updateHashMap();
        $account->updateSerial();
    }

    /**
     * Handle the organization "deleted" event.
     *
     * @param Account $account
     * @return void
     */
    public function deleted(Account $account)
    {
        if ($account->parent) {
            $account->parent->updateHashMap();
        }

    }


}
