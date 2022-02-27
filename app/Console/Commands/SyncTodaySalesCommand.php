<?php

namespace App\Console\Commands;

use App\Jobs\QuickBooks\SalesQuickBooksSyncJob;
use App\Models\Invoice;
use App\Models\Manager;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SyncTodaySalesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SyncTodaySalesCommand';

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
     * @return int
     */
    public function handle()
    {
        $manager = Manager::whereEmail("ali@msbrshop.com")->first();
        foreach(Invoice::whereDate('created_at',Carbon::today())->whereOrganizationId(1)->get() as $invoice) {
            dispatch_sync(new SalesQuickBooksSyncJob($invoice,$manager ));
        }
        return Command::SUCCESS;
    }
}
