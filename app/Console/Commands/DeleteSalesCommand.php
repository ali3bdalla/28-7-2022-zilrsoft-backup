<?php

namespace App\Console\Commands;

use App\Invoice;
use Illuminate\Console\Command;
use Modules\Sales\Jobs\DeleteSaleInvoiceJob;

class DeleteSalesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:delete_sales_command';

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
//        $invoices = Invoice::whereIn()
//        foreach ($invoices as $invoice)
//        {
//            dispatch(new DeleteSaleInvoiceJob($invoice));
//        }
    }
}
