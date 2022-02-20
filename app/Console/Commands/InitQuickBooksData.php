<?php

namespace App\Console\Commands;

use App\Jobs\QuickBooks\CustomerSyncJob;
use App\Jobs\QuickBooks\ItemSyncJob;
use App\Jobs\SyncQuickBooksClassJob;
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
    public function handle()
    {
        foreach (User::whereIsClient(true)->with("organization")->get() as $user) {
            dispatch(new CustomerSyncJob($user, Manager::whereEmail("ali@msbrshop.com")->first()));
        }
        foreach (Manager::query()->get() as $user) {
            dispatch(new SyncQuickBooksClassJob($user, Manager::whereEmail("ali@msbrshop.com")->first()));
        }
        foreach (Item::whereIsKit(false)->with("organization")->get() as $item) {
            dispatch(new ItemSyncJob($item, Manager::whereEmail("ali@msbrshop.com")->first()));
        }
        return Command::SUCCESS;
    }
}
