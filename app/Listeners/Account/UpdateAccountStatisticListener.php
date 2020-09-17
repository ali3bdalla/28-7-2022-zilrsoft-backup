<?php

namespace App\Listeners\Account;

use App\Models\Account;
use App\Events\Transaction\TransactionCreatedEvent;
use App\Events\Transaction\TransactionErasedEvent;
use App\Models\Transaction;


class UpdateAccountStatisticListener
{

    public $event;
    public $stockAccount;

    public function __construct()
    {
        $this->stockAccount = Account::getUsingSlug('stock');
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->event = $event;

        if ($event instanceof TransactionCreatedEvent)
            $this->_newTransactionUpdate();

        if ($event instanceof TransactionErasedEvent) {
            $this->_eraseTransactionUpdate();
        }
    }




    private function _newTransactionUpdate()
    {

        $amount = (float)$this->event->transaction->amount;

        if ($this->event->transaction->_isDebitAbleAccount()) {
            $this->event->transaction->_getDebitAbleAccount()->_getAccountBalanceUsingTransactions();

            // $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            // $newAmount = (float)$statisticInstance->debit_amount  + (float)$amount;
            // $statisticInstance->update([
            //     'debit_amount' => $newAmount
            // ]);
        }


        if ($this->event->transaction->_isCreditAbleAccount()) {
            $this->event->transaction->_getCreditAbleAccount()->_getAccountBalanceUsingTransactions();
            // $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();
            // $newAmount = (float)$statisticInstance->credit_amount  + (float)$amount;

            // $statisticInstance->update([
            //     'credit_amount' => $newAmount
            // ]);
        }


        if ($this->event->transaction->_isDebitAbleItem()) {
            $this->stockAccount->_getStockBalanceUsingTransactions();

            // $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            // $newAmount = (float)$statisticInstance->debit_amount  + (float)$amount;

            // $statisticInstance->update([
            //     'debit_amount' => $newAmount
            // ]);
        }



        if ($this->event->transaction->_isCreditAbleItem()) {
            $this->stockAccount->_getStockBalanceUsingTransactions();

            // $statisticInstance = $this->stockAccount->_getStatisticsInstance();

            // $newAmount = (float)$statisticInstance->credit_amount  + (float)$amount;
            // // die($statisticInstance->credit_amount);

            // $statisticInstance->update([
            //     'credit_amount' =>$newAmount
            // ]);
        }
    }


    private function _eraseTransactionUpdate()
    {
        $amount = (float)$this->event->transaction->moneyFormatter($this->event->transaction->amount);
        //

        if ($this->event->transaction->_isDebitAbleAccount()) {

            $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            $newAmount = (float)$statisticInstance->debit_amount  - (float)$amount;

            $statisticInstance->update([
                'debit_amount' => $newAmount
            ]);
        }


        if ($this->event->transaction->_isCreditAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();
            $newAmount = (float)$statisticInstance->credit_amount  - (float)$amount;


            $statisticInstance->update([
                'credit_amount' =>$newAmount
            ]);
        }


        if ($this->event->transaction->_isDebitAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $newAmount = (float)$statisticInstance->debit_amount  - (float)$amount;

            $statisticInstance->update([
                'debit_amount' => $newAmount
            ]);
        }



        if ($this->event->transaction->_isCreditAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $newAmount = (float)$statisticInstance->credit_amount  - (float)$amount;

            $statisticInstance->update([
                'credit_amount' => $newAmount
            ]);
        }
    }
}
