<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Models\Account;
use App\Models\Item;
use App\Models\Manager;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCloseYearInventoryTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TransactionsContainer
     */
    private $transactionsContainer;
    /**
     * @var Account
     */
    private $stockAccount;
    /**
     * @var Item
     */
    private $item;
    /**
     * @var Manager
     */
    private $loggedUser;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $transactionsContainer
     * @param Account $stockAccount
     * @param Item $item
     * @param Manager $loggedUser
     */
    public function __construct(TransactionsContainer $transactionsContainer,Account  $stockAccount,Item  $item,Manager $loggedUser)
    {
        //
        $this->transactionsContainer = $transactionsContainer;
        $this->stockAccount = $stockAccount;
        $this->item = $item;
        $this->loggedUser = $loggedUser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $debitAmount = Transaction::where([
            ['item_id',$this->item->id],
            ['account_id',$this->stockAccount->id],
            ['type','debit']
        ])->whereDate('created_at','<',Carbon::parse($this->transactionsContainer->created_at)->toDate())->sum('amount');
        $creditAmount = Transaction::where([
            ['item_id',$this->item->id],
            ['account_id',$this->stockAccount->id],
            ['type','credit']
        ])->whereDate('created_at','<',Carbon::parse($this->transactionsContainer->created_at)->toDate())->sum('amount');
        $balance = $debitAmount - $creditAmount;

        if($balance != 0) {
            CreateCloseYearTransactionJob::dispatchNow($this->transactionsContainer,$this->stockAccount,$balance,$this->loggedUser,['item_id' => $this->item->id]);
        }
    }
}
