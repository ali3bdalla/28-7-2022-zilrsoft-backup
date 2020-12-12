<?php

namespace App\Console\Commands\Entity;

use App\Jobs\Accounting\Entity\UpdateAccountBalanceJob;
use App\Jobs\User\Balance\UpdateClientBalanceJob;
use App\Jobs\User\Balance\UpdateVendorBalanceJob;
use App\Models\Account;
use App\Models\AccountSnapshot;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Carbon\Carbon;
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

        $accounts = Account::all();
        foreach ($accounts as $account) {
            $snapTotalCredit = $account->snapshots()->sum('credit_amount');
            $snapTotalDebit = $account->snapshots()->sum('debit_amount');


            $account->update([
                'total_credit_amount' => $snapTotalCredit,
                'total_debit_amount' => $snapTotalDebit,
            ]);
//            if($snapTotalCredit != $account->total_credit_amount){
//                dd($account->total_credit_amount - $snapTotalCredit,$account->toArray());
//            }
//
//
//            if($snapTotalDebit != $account->total_debit_amount){
//                dd($account->total_debit_amount - $snapTotalDebit);
//            }
        }

//        $items = Item::where('is_need_serial',true)->get();
//        foreach ( $items as $item) {
//            $serials = $item->serials()->whereIn('status',['in_stock','return_sale'])->count();
//            $item->update([
//                'available_qty' => $serials
//            ]);
//        }

//        $date = Carbon::parse('11/30/2020');
//        $containers = TransactionsContainer::whereDate('created_at',$date)->get();
//
//        $snapTotalCredit = AccountSnapshot::whereDate('created_at',$date)->sum('credit_amount');
//        $snapTotalDebit = AccountSnapshot::whereDate('created_at',$date)->sum('debit_amount');
//
//
//
//        $totalCredit = 0;
//        $totalDebit = 0;
//        foreach ($containers as $container){
//            $transactions = $container->transactions;
//            $debit = 0;
//            $credit = 0;
//            foreach ($transactions as $transaction ){
//
////                dispatch(new UpdateAccountBalanceJob($transaction, false));
////                dispatch(new UpdateAccountBalanceJob($transaction, true));
//
//
//                if($transaction->type =='credit')
//                {
//                    $credit+=$transaction->amount;
//                    $totalCredit+=$transaction->amount;
//                }else{
//                    $debit+=$transaction->amount;
//                    $totalDebit+=$transaction->amount;
//                }
//            }
//
//            $variation = abs($credit - $debit);
//            if($variation > 1)
//            {
//                dd($container->toArray());
//            }
//        }
//
//        dd($totalCredit,$snapTotalCredit,$totalDebit,$snapTotalDebit);
//        $entityId = $this->argument('entityId');
//        $amount = $this->argument('amount');
//
//        if ($entityId && $amount) {
//            $transaction = Transaction::find($entityId);
//            if ($transaction) {
//                if ($transaction->account->slug == 'vendors') {
//                    dispatch(new UpdateVendorBalanceJob($transaction->user, $amount, 'decrease'));
//                }
//                if ($transaction->account->slug == 'clients') {
//                    dispatch(new UpdateClientBalanceJob($transaction->user, $amount, 'decrease'));
//                }
//
//                $diff = $transaction->amount - $amount;
//                if ($diff > 0) {
//                    $transaction->update([
//                        'amount' => $diff
//                    ]);
//                    dispatch(new UpdateAccountBalanceJob($transaction, false, true, $amount));
//
//                } else
//                {
//                    $transaction->update([
//                        'amount' => abs($diff),
//                        'type' => 'debit'
//                    ]);
//                    dispatch(new UpdateAccountBalanceJob($transaction, true, true, $amount));
//
//                }
//
//            }

//        }


//        152735 660
        //154874 1379.44
        // 156012 149.99
        //155173  310


    }
}
