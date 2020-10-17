<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\TransactionsContainer;
use Illuminate\Console\Command;

class CheckTransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:check_transaction_command';

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
        $containers = TransactionsContainer::where('id','<',16959)->orderBy('id','desc')->get();

        foreach ($containers as $container) {
            echo $container->id . "\n";
            $debitAmount = $container->transactions()->where('type', 'debit')->sum('amount');
            $creditAmount = $container->transactions()->where('type', 'credit')->sum('amount');

         
            $def =(float) ( $debitAmount - $creditAmount);
            if (abs($def) >= 0.0001) {
                dd($container->id,$debitAmount - $creditAmount);
            }
        }

    }
}
