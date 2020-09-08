<?php

namespace App\Console\Commands\Accounting\Transaction;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DetectDoublePartyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:accounting_transaction_detect_double_party';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to detect double party transactions';

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
        $containers = Transaction::where([
            ['debitable_type','!=',null ],
            ['debitable_id','!=',null ],
            ['creditable_type','!=',null ],
            ['creditable_id','!=',null ]
        ])->select('container_id')->distinct()->get('container_id')->pluck('container_id')->toArray();


        DB::connection('placeholder')->table('transactions')->insert([
            'description' =>  'accounting_transaction_detect_double_party',
            'content' => implode(',',$containers)
        ]);
        dd(Transaction::count(),count($containers),Transaction::count() - count($containers));
    }
}
