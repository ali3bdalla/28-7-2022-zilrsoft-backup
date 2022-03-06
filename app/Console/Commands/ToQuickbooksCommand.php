<?php

namespace App\Console\Commands;

use App\Enums\InvoiceTypeEnum;
use App\Jobs\QuickBooks\BillQuickBooksSyncJob;
use App\Jobs\QuickBooks\RefundBillQuickBooksSyncJob;
use App\Jobs\QuickBooks\RefundSalesQuickBooksSyncJob;
use App\Jobs\QuickBooks\SalesQuickBooksSyncJob;
use App\Models\Invoice;
use App\Models\Manager;
use Illuminate\Console\Command;

class ToQuickbooksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ToQuickbooksCommand';

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
        foreach (Invoice::query()->whereNull('quickbooks_id')->whereHas("user", function ($subQuery) {
            return $subQuery->whereNotNull('quickbooks_vendor_id');
        })->whereIn('invoice_type', [InvoiceTypeEnum::purchase()])->whereOrganizationId(1)->whereYear("created_at", '>=', '2021')->whereNull('quickbooks_id')->get() as $invoice) {
            dispatch(new BillQuickBooksSyncJob($invoice, $manager));
        }

        foreach (Invoice::query()->whereNull('quickbooks_id')->whereHas("user", function ($subQuery) {
            return $subQuery->whereNotNull('quickbooks_customer_id');
        })->whereIn('invoice_type', [InvoiceTypeEnum::return_sale()])->whereOrganizationId(1)->whereYear("created_at", '>=', '2021')->whereNull('quickbooks_id')->get() as $invoice) {
            dispatch(new RefundSalesQuickBooksSyncJob($invoice, $manager));
        }


        foreach (Invoice::query()->whereNull('quickbooks_id')->whereHas("user", function ($subQuery) {
            return $subQuery->whereNotNull('quickbooks_vendor_id');
        })->whereIn('invoice_type', [InvoiceTypeEnum::return_purchase()])->whereOrganizationId(1)->whereYear("created_at", '>=', '2021')->whereNull('quickbooks_id')->get() as $invoice) {
            dispatch(new RefundBillQuickBooksSyncJob($invoice, $manager));
        }

        foreach (Invoice::query()->whereNull('quickbooks_id')->whereHas("user", function ($subQuery) {
            return $subQuery->whereNotNull('quickbooks_customer_id');
        })->whereIn('invoice_type', [InvoiceTypeEnum::sale()])->whereOrganizationId(1)->whereYear("created_at", '>=', '2021')->whereNull('quickbooks_id')->get() as $invoice) {
            dispatch(new SalesQuickBooksSyncJob($invoice, $manager));
        }
        return Command::SUCCESS;
    }
}
