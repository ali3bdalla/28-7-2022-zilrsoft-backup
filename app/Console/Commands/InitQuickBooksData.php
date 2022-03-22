<?php

namespace App\Console\Commands;

use App\Enums\InvoiceTypeEnum;
use App\Enums\VoucherTypeEnum;
use App\Jobs\QuickBooks\BillQuickBooksSyncJob;
use App\Jobs\QuickBooks\CategoryQuickBooksSyncJob;
use App\Jobs\QuickBooks\ClassificationQuickBooksSyncJob;
use App\Jobs\QuickBooks\CustomerQuickBooksSyncJob;
use App\Jobs\QuickBooks\ItemQuickBooksSyncJob;
use App\Jobs\QuickBooks\PaymentQuickBooksSyncJob;
use App\Jobs\QuickBooks\RefundBillQuickBooksSyncJob;
use App\Jobs\QuickBooks\RefundSalesQuickBooksSyncJob;
use App\Jobs\QuickBooks\SalesQuickBooksSyncJob;
use App\Jobs\QuickBooks\VendorQuickBooksSyncJob;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Console\Command;

class InitQuickBooksData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'InitQuickBooksData';

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
    public function handle(): int
    {

        $manager = Manager::whereEmail("mahmoud@msbrshop.com")->first();
        foreach (User::query()->whereNull("quickbooks_customer_id")->whereOrganizationId(1)->whereIsClient(true)->with("organization")->get() as $user) {
            dispatch(new CustomerQuickBooksSyncJob($user, $manager));
        }
        foreach (User::whereIsVendor(true)->whereNull("quickbooks_vendor_id")->whereOrganizationId(1)->with("organization", 'details')->get() as $user) {
            dispatch(new VendorQuickBooksSyncJob($user, $manager));
        }
        foreach (Manager::query()->whereNull("quickbooks_class_id")->whereOrganizationId(1)->get() as $user) {
            dispatch_sync(new ClassificationQuickBooksSyncJob($user, $manager));
        }
        foreach (Category::query()->whereNull("quickbooks_id")->whereOrganizationId(1)->with('organization')->get() as $category) {
            dispatch(new CategoryQuickBooksSyncJob($category, $manager));
        }
        foreach (Item::whereIsService(true)->whereNull("quickbooks_id")->whereIsKit(false)->whereOrganizationId(1)->with("organization", 'category')->get() as $item) {
            dispatch(new ItemQuickBooksSyncJob($item, $manager));
        }
        foreach (Item::whereIsService(false)->whereNull("quickbooks_id")->whereIsKit(false)->whereOrganizationId(1)->with("organization", 'category')->get() as $item) {
            dispatch(new ItemQuickBooksSyncJob($item, $manager));
        }
        foreach (Invoice::query()
                     ->whereNull('quickbooks_id')
                     ->whereYear("created_at", ">=", "2021")
                     ->whereIn('invoice_type', [InvoiceTypeEnum::sale()])
                     ->where("organization_id", 1)
                     ->get() as $invoice) {
            dispatch(new SalesQuickBooksSyncJob($invoice, $manager));
        }

        foreach (Invoice::query()
                     ->whereNull('quickbooks_id')
                     ->whereYear("created_at", ">=", "2021")
                     ->whereIn('invoice_type', [InvoiceTypeEnum::purchase()])
                     ->where("organization_id", 1)
                     ->get() as $invoice) {
            dispatch(new BillQuickBooksSyncJob($invoice, $manager));
        }
        foreach (Invoice::query()
                     ->whereNull('quickbooks_id')
                     ->whereYear("created_at", ">=", "2021")
                     ->whereIn('invoice_type', [InvoiceTypeEnum::return_purchase()])
                     ->where("organization_id", 1)
                     ->get() as $invoice) {
            dispatch(new RefundBillQuickBooksSyncJob($invoice, $manager));
        }
        foreach (Invoice::query()
                     ->whereNull('quickbooks_id')
                     ->whereYear("created_at", ">=", "2021")
                     ->whereIn('invoice_type', [InvoiceTypeEnum::return_sale()])
                     ->where("organization_id", 1)
                     ->get() as $invoice) {
            dispatch(new RefundSalesQuickBooksSyncJob($invoice, $manager));
        }

        foreach (Voucher::query()
                     ->whereHas("user", function ($user) {
                         return $user->whereNotNull('quickbooks_customer_id');
                     })
                     ->whereNull("quickbooks_id")
                     ->whereNull("invoice_id")
                     ->where('payment_type', VoucherTypeEnum::receipt())
                     ->whereYear("created_at", ">=", "2021")->where('organization_id', 1)->get() as $voucher) {
            dispatch(new PaymentQuickBooksSyncJob($voucher, $manager));
        }
        return 0;
    }
}
