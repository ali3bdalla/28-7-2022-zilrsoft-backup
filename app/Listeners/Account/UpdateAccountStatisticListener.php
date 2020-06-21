<?php

namespace App\Listeners\Account;

use App\Account;
use App\AccountStatistic;
use App\Events\Transaction\TransactionCreatedEvent;
use App\Events\Transaction\TransactionErasedEvent;
use App\Item;
use App\Transaction;
use Illuminate\Support\Facades\DB;

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

        if($event instanceof TransactionCreatedEvent)
             $this->_newTransactionUpdate();
        if($event instanceof TransactionErasedEvent)
        {
            $this->_eraseTransactionUpdate();
        }
    }




    private function _newTransactionUpdate()
    {

        if ($this->event->transaction->_isDebitAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getDebitAbleAccount()->_isCredit()) {
                $balance = $statisticInstance->getOriginal('total_amount') - $this->event->transaction->amount;
            } else {
                $balance = $statisticInstance->getOriginal('total_amount') + $this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance,true);

        }

        if ($this->event->transaction->_isCreditAbleAccount()) {

            $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getCreditAbleAccount()->_isCredit()) {
                $balance = $statisticInstance->total_amount + $this->event->transaction->amount;
            } else {
                $balance = $statisticInstance->getOriginal('total_amount') - $this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance,true);
        }


        if ($this->event->transaction->_isDebitAbleItem()) {
            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
            ($statisticInstance,
                ($this->event->transaction->amount + $statisticInstance->total_amount),
                true);

        }

        if ($this->event->transaction->_isCreditAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
            ($statisticInstance,
                ($this->event->transaction->amount - $statisticInstance->total_amount),
                true);

        }

    }
    private function _eraseTransactionUpdate()
    {
        if ($this->event->transaction->_isDebitAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getDebitAbleAccount()->_isCredit()) {
                $balance = $statisticInstance->getOriginal('total_amount') + $this->event->transaction->amount;
            } else {
                $balance = $statisticInstance->getOriginal('total_amount') - $this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance,false);
        }

        if ($this->event->transaction->_isCreditAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getCreditAbleAccount()->type == 'credit') {
                $balance = $statisticInstance->getOriginal('total_amount') - $this->event->transaction->amount;
            } else {
                $balance = $statisticInstance->getOriginal('total_amount') + $this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance,false);

        }
        if ($this->event->transaction->_isDebitAbleItem()) {
            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
            ($statisticInstance,
                ($this->event->transaction->amount - $statisticInstance->total_amount),
                false);
        }
        if ($this->event->transaction->_isCreditAbleItem()) {
            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
                ($statisticInstance,
                ($this->event->transaction->amount + $statisticInstance->total_amount),
                false);
        }
    }


    private function _updateAccountBalanceAndTransactionsCount(AccountStatistic $accountStatistic,$balance,$addTransaction = true)
    {
        $accountStatistic->update([
            'transactions_count' => $addTransaction ?  DB::raw("transactions_count + 1") : DB::raw("transactions_count - 1"),
            'total_amount' => $balance,
        ]);
    }

    


    
    
}
