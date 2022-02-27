<?php

namespace App\Console\Commands;

use App\Jobs\QuickBooks\CategoryQuickBooksSyncJob;
use App\Jobs\QuickBooks\ClassificationQuickBooksSyncJob;
use App\Jobs\QuickBooks\CustomerQuickBooksSyncJob;
use App\Jobs\QuickBooks\ItemQuickBooksSyncJob;
use App\Jobs\QuickBooks\VendorQuickBooksSyncJob;
use App\Models\Category;
use App\Models\Item;
use App\Models\Manager;
use App\Models\User;
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

        $manager = Manager::whereEmail("ali@msbrshop.com")->first();
        foreach (User::query()->whereOrganizationId(1)->whereIsClient(true)->with("organization")->get() as $user) {
            dispatch(new CustomerQuickBooksSyncJob($user, $manager));
        }
        foreach (User::whereIsVendor(false)->whereOrganizationId(1)->with("organization", 'details')->get() as $user) {
            dispatch(new VendorQuickBooksSyncJob($user, $manager));
        }
        foreach (Manager::query()->whereOrganizationId(1)->get() as $user) {
            dispatch_sync(new ClassificationQuickBooksSyncJob($user, $manager));
        }
        foreach (Category::query()->whereOrganizationId(1)->with('organization')->get() as $category) {
            dispatch(new CategoryQuickBooksSyncJob($category, $manager));
        }
        foreach (Item::whereIsService(true)->whereIsKit(false)->whereOrganizationId(1)->with("organization", 'category')->get() as $item) {
            dispatch(new ItemQuickBooksSyncJob($item, $manager));
        }
        foreach (Item::whereIsService(false)->whereIsKit(false)->whereOrganizationId(1)->with("organization", 'category')->get() as $item) {
            dispatch(new ItemQuickBooksSyncJob($item, $manager));
        }
        return 0;
    }
}
