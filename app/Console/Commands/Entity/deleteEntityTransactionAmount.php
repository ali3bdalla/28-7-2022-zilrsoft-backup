<?php

namespace App\Console\Commands\Entity;

use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;
use App\Jobs\User\Balance\UpdateClientBalanceJob;
use App\Jobs\User\Balance\UpdateVendorBalanceJob;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Illuminate\Console\Command;

class deleteEntityTransactionAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:deleteEntityTransactionAmount {entityId} {amount}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $entityId = $this->argument('entityId');
        $amount = $this->argument('amount');

        if ($entityId && $amount) {
            $transaction = Transaction::find($entityId);
            if ($transaction) {
                if ($transaction->account->slug == 'vendors') {
                    dispatch(new UpdateVendorBalanceJob($transaction->user, $amount, 'decrease'));
                }
                if ($transaction->account->slug == 'clients') {
                    dispatch(new UpdateClientBalanceJob($transaction->user, $amount, 'decrease'));
                }

                $diff = $transaction->amount - $amount;
                if ($diff > 0) {
                    $transaction->update([
                        'amount' => $diff
                    ]);
                    dispatch(new UpdateAccountBalanceJob($transaction, false, true, $amount));

                } else
                {
                    $transaction->update([
                        'amount' => abs($diff),
                        'type' => 'debit'
                    ]);
                    dispatch(new UpdateAccountBalanceJob($transaction, true, true, $amount));

                }

//                $transaction->forceDelete();


            }

        }


//        152735 660
        //154874 1379.44
        // 156012 149.99
        //155173  310


    }
}
