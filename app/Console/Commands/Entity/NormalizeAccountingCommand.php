<?php

namespace App\Console\Commands\Entity;

use App\Models\Account;
use App\Models\AccountSnapshot;
use App\Models\TransactionsContainer;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NormalizeAccountingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:NormalizeAccountingCommand';

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


//         $between = [Carbon::parse('22-11-2020'),Carbon::parse('24-11-2020')];
//          AccountSnapshot::whereBetween('created_at',$between)->update([
//             'credit_amount' =>0 ,
//             'debit_amount' => 0
//         ]);
//         // $totalDebit = AccountSnapshot::whereBetween('created_at',$between)->sum('debit_amount');

//         // dd($totalCredit,$totalDebit);


//         entities = TransactionsContainer::whereBetween('created_at',$between)->get();

//         foreach ($entities as $key => $entity) {
            
//             $transactions = $entity->transactions;

//             // $entityCredit = 0;
//             // $entityDebit = 0;
//             foreach($transactions as $transaction)
//             {

//                 $snapshot = $transaction->account->snapshots()->whereDate('created_at',$transaction->created_at)->first();
//                 // if($transaction->type === 'credit')
                    
//                 // else
//                 //     $entityDebit+=$transaction->amount;
//             }


//             // if(abs($entityCredit -  $entityDebit) >= 0.2)
//             // {
//             //     dd($entityCredit,  $entityDebit,$entity->toArray());
//             // }

//         }



//         $accounts = Account::all();
//         foreach ($accounts as $account) {
//             $snapTotalCredit $= $account->snapshots()->sum('credit_amount');
//             $snapTotalDebit = $account->snapshots()->sum('debit_amount');


//             $account->update([
//                 'total_credit_amount' => $snapTotalCredit,
//                 'total_debit_amount' => $snapTotalDebit,
//             ]);
// //            if($snapTotalCredit != $account->total_credit_amount){
// //                dd($account->total_credit_amount - $snapTotalCredit,$account->toArray());
// //            }
// //
// //
// //            if($snapTotalDebit != $account->total_debit_amount){
// //                dd($account->total_debit_amount - $snapTotalDebit);
// //            }
//         }
    }
}
