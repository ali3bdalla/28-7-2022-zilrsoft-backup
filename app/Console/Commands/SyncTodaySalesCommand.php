<?php

namespace App\Console\Commands;

use App\Enums\InvoiceTypeEnum;
use App\Jobs\QuickBooks\BillQuickBooksSyncJob;
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
//        whereDate('created_at', Carbon::today()->subDay())
        foreach (Invoice::query()->whereNull('quickbooks_id')->whereIn('invoice_type', [InvoiceTypeEnum::purchase()])->whereOrganizationId(1)->take(1)->get() as $invoice) {
            dispatch_sync(new BillQuickBooksSyncJob($invoice, $manager));
        }
//        foreach (Invoice::whereDate('created_at', Carbon::today()->subDay())->whereNull('quickbooks_id')->whereIn('invoice_type',[InvoiceTypeEnum::sale()])->whereOrganizationId(1)->get() as $invoice) {
//            dispatch_sync(new SalesQuickBooksSyncJob($invoice, $manager));
//        }
//        foreach (Invoice::whereDate('created_at', Carbon::today()->subDay())->whereNull('quickbooks_id')->whereIn('invoice_type',[InvoiceTypeEnum::return_sale()])->whereOrganizationId(1)->get() as $invoice) {
//            dispatch_sync(new RefundSalesQuickBooksSyncJob($invoice, $manager));
//        }

        return Command::SUCCESS;
    }
}
