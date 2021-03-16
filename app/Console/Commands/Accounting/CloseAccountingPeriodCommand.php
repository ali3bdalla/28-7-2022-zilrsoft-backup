<?php

namespace App\Console\Commands\Accounting;

use App\Jobs\Accounting\CloseYear\CreateCloseYearEntityJob;
use App\Models\Manager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CloseAccountingPeriodCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:accounting_close_accounting_period_command';

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
        CreateCloseYearEntityJob::dispatch(Manager::find(1));
    }
}
