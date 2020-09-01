<?php

namespace App\Console\Commands;

use App\TransactionsContainer;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteDirectEntityCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete_direct_entity_command';

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
        DB::beginTransaction();
        try {
           

            $escapeInvoice = [];

            $entities = TransactionsContainer::where('invoice_id',   0)->get();
            // $invoices = Invoice::find([]);
            foreach ($entities as $etity) {
                
                $creditAmount = $etity->transactions()->where([
                    ['creditable_type','!=',null],
                    ['creditable_id','!=',null],
                ])->sum('amount');
                $debitAmount  = $etity->transactions()->where([
                    ['debitable_type','!=',null],
                    ['debitable_id','!=',null],
                ])->sum('amount');

                // dd($creditAmount,$debitAmount);
              
                if($creditAmount != $debitAmount)
                {
                    echo $etity->id . "\n";
                    $etity->transactions()->forceDelete();
                    $etity->forceDelete();
                }
                

            }
           
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
