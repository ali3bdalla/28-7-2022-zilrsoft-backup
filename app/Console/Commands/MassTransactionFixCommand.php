<?php

namespace App\Console\Commands;

use App\TransactionsContainer;
use Illuminate\Console\Command;
use phpDocumentor\Reflection\Types\Null_;

class MassTransactionFixCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix-mass-transactions';

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
        $transactionsContainers = TransactionsContainer::orderBy('id','desc')->get();

        $mass = [];
        foreach($transactionsContainers as $container)
        {
            $credit = $container->transactions()->where('creditable_type','!=',null)->sum('amount');
            $debit = $container->transactions()->where('debitable_type','!=',null)->sum('amount');

            $def = $credit - $debit;

            if($def < 1 && $def > -1)
            {
                
            }else
            {
                echo $container->id . "\n";
                // $mass[] = $container->id;
            }
        }


        
    }
}
