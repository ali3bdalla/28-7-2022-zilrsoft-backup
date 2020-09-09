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

        // if($event instanceof TransactionCreatedEvent)
        //      $this->_newTransactionUpdate();
             
        // if($event instanceof TransactionErasedEvent)
        // {
        //     $this->_eraseTransactionUpdate();
        // }
    }




    private function _newTransactionUpdate()
    {
 
        $amount = (float)$this->event->transaction->amount;
    
        if ($this->event->transaction->_isDebitAbleAccount()) {
            
            $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            $statisticInstance->update([
                'debit_amount' => $statisticInstance->debit_amount  + $amount
            ]);
        

        }
        
        
        if ($this->event->transaction->_isCreditAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();

            $statisticInstance ->update([
                'credit_amount' => $statisticInstance->credit_amount  + $amount
            ]);
           
        }


        if ($this->event->transaction->_isDebitAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $statisticInstance ->update([
                'debit_amount' => $statisticInstance->debit_amount + $amount
            ]);
     
        }



        if ($this->event->transaction->_isCreditAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $statisticInstance->update([
                'credit_amount' =>$statisticInstance->credit_amount + $amount
            ]);
     

        }


    }


    private function _eraseTransactionUpdate()
    {
        $amount = (float)$this->event->transaction->amount;
    
        if ($this->event->transaction->_isDebitAbleAccount()) {
            
            $statisticInstance = $this->event->transaction->_getDebitAbleAccount()->_getStatisticsInstance();
            $statisticInstance->update([
                'debit_amount' => $statisticInstance->debit_amount  - $amount
            ]);
     

        }
        
        
        if ($this->event->transaction->_isCreditAbleAccount()) {
            $statisticInstance = $this->event->transaction->_getCreditAbleAccount()->_getStatisticsInstance();

            $statisticInstance ->update([
                'credit_amount' => $statisticInstance->credit_amount  - $amount
            ]);
         
        }


        if ($this->event->transaction->_isDebitAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $statisticInstance ->update([
                'debit_amount' => $statisticInstance->debit_amount - $amount
            ]);
      
        }



        if ($this->event->transaction->_isCreditAbleItem()) {

            $statisticInstance = $this->stockAccount->_getStatisticsInstance();
            $statisticInstance->update([
                'credit_amount' =>$statisticInstance->credit_amount - $amount
            ]);
     
       

        }
    }


    
}
