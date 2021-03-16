<?php

namespace App\Jobs\Accounting\CloseYear;

use App\Models\Account;
use App\Models\Item;
use App\Models\Manager;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateCloseYearInventoryTransactionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var TransactionsContainer
     */
    private $transactionsContainer;
    /**
     * @var Manager
     */
    private $loggedUser;

    private $stockAccount;

    /**
     * Create a new job instance.
     *
     * @param TransactionsContainer $transactionsContainer
     * @param Manager $loggedUser
     */
    public function __construct(TransactionsContainer $transactionsContainer, Manager $loggedUser)
    {
        //
        $this->transactionsContainer = $transactionsContainer;
        $this->loggedUser = $loggedUser;
        $this->stockAccount = Account::where('slug', 'stock')->first();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function() {
            Item::where('is_kit', false)->each(function ($item) {
                CreateCloseYearInventoryTransactionJob::dispatch($this->transactionsContainer,$this->stockAccount,$item,$this->loggedUser);
            });
        });
    }

}
