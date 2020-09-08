<?php

namespace App\Console\Commands\Accounting\Transaction;

use App\Account;
use App\Transaction;
use App\TransactionsContainer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class NormalizeDirectTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:accounting_transaction_normalize_direct_transaction';

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

        // $placeholder = DB::connection('placeholder')->table('transactions')->where('description','accounting_transaction_detect_double_party')->orderBy('id','desc')->first();
        // Transaction::whereIn('container_id',explode(',',$placeholder->content))->forceDelete();
        // TransactionsContainer::whereIn('id',explode(',',$placeholder->content))->forceDelete();
        


        // Transaction::where('invoice_id','!=',null)->forceDelete();
        // TransactionsContainer::where('invoice_id','!=',null)->forceDelete();


    }
}
