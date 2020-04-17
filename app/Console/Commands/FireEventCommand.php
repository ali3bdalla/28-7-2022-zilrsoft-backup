<?php

namespace App\Console\Commands;

use App\Events\Accounting\Invoice\PendingPurchaseInvoiceCreatedEvent;
use Illuminate\Console\Command;

class FireEventCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fire_events';

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
//    	dd(1);
    	     event(new PendingPurchaseInvoiceCreatedEvent("message"));
        //
    }
}
