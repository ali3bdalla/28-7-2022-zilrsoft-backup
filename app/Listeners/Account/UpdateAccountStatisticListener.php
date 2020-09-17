<?php

namespace App\Listeners\Account;

use App\Models\Account;
use App\Models\AccountStatistic;
use App\Events\Transaction\TransactionCreatedEvent;
use App\Events\Transaction\TransactionErasedEvent;
use App\Models\Item;
use App\Models\Transaction;
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


//        dd($this->event->transaction);
        if ($this->event->transaction->_isDebitAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getDebitAbleAccount()->_isCredit()) {
                $balance = (float)$statisticInstance->getOriginal('total_amount') - (float)$this->event->transaction->amount;
            } else {
                $balance = (float)$statisticInstance->getOriginal('total_amount') + (float)$this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance,(float)$this->event->transaction->amount,
                false,true);

        }

        if ($this->event->transaction->_isCreditAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getCreditAbleAccount()->_isCredit()) {
                $balance = (float)$statisticInstance->total_amount + (float)$this->event->transaction->amount;
            } else {
                $balance = (float)$statisticInstance->getOriginal('total_amount') - (float)$this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance,(float)$this->event->transaction->amount,
                true,true);
        }


        if ($this->event->transaction->_isDebitAbleItem()) {
            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
            ($statisticInstance,
                ((float)$this->event->transaction->amount + (float)$statisticInstance->total_amount),
                (float)$this->event->transaction->amount,
                false,
                true);

        }

        if ($this->event->transaction->_isCreditAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
            ($statisticInstance,
                ((float)$this->event->transaction->amount - (float)$statisticInstance->total_amount),
                (float)$statisticInstance->total_amount,
                true,
                true);

        }

    }
    private function _eraseTransactionUpdate()
    {
        if ($this->event->transaction->_isDebitAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getDebitAbleAccount()->_isCredit()) {
                $balance = $statisticInstance->getOriginal('total_amount') + (float)$this->event->transaction->amount;
            } else {
                $balance = $statisticInstance->getOriginal('total_amount') - (float)$this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance ,(float)$this->event->transaction->amount,
                false,false);
        }

        if ($this->event->transaction->_isCreditAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();
            if ($this->event->transaction->_getCreditAbleAccount()->type == 'credit') {
                $balance = $statisticInstance->getOriginal('total_amount') - (float)$this->event->transaction->amount;
            } else {
                $balance = $statisticInstance->getOriginal('total_amount') + (float)$this->event->transaction->amount;
            }
            $this->_updateAccountBalanceAndTransactionsCount($statisticInstance, $balance,(float)$this->event->transaction->amount,
                true,false);

        }
        if ($this->event->transaction->_isDebitAbleItem()) {
            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
            ($statisticInstance,
                ((float)$this->event->transaction->amount - $statisticInstance->total_amount),
                (float)$this->event->transaction->amount,
                false,
                false);
        }
        if ($this->event->transaction->_isCreditAbleItem()) {
            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $this->_updateAccountBalanceAndTransactionsCount
                ($statisticInstance,
                ((float)$this->event->transaction->amount + $statisticInstance->total_amount),
                (float)$this->event->transaction->amount,
                true,
                false);
        }
    }


    private function _updateAccountBalanceAndTransactionsCount(AccountStatistic $accountStatistic,$balance,$amount , $isCredit = true,$addTransaction = true)
    {

        $data = [
            'transactions_count' => $addTransaction ?  $accountStatistic->transactions_count + 1 : $accountStatistic->transactions_count - 1,
            'total_amount' => $balance,
        ];

        if($isCredit)
        {
            $data['credit_amount'] = $addTransaction ? $accountStatistic->credit_amount +  $amount : $accountStatistic->credit_amount -  $amount;
        }else
        {
            $data['debit_amount'] = $addTransaction ? $accountStatistic->debit_amount +  $amount : $accountStatistic->debit_amount -  $amount;

        }
        $accountStatistic->update($data);
    }

    


    
    
}
