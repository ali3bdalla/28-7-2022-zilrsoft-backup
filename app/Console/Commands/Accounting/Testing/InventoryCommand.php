<?php

namespace App\Console\Commands\Accounting\Testing;

use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InventoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:accounting_testing_inventory';

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
        $itemsIds = DB::table('items')->where([
            [

                'organization_id' , 1,
            ],
            [
                'is_kit', false
            ]
        ])->pluck('id')->toArrAy();


        $creditAmount = DB::table('transactions')->whereIn('item_id',$itemsIds)->where('type','credit')->whereDate('created_at','<',Carbon::parse('2021-01-01'))->sum('amount');
        $debitAmount = DB::table('transactions')->whereIn('item_id',$itemsIds)->where('type','debit')->whereDate('created_at','<',Carbon::parse('2021-01-01'))->sum('amount');
        dd($creditAmount,$debitAmount);

    }
}
