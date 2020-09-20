<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class CreateAccountsHashMapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:create_accounts_hashmap_command';

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

        $mainAccounts = Account::withCount('children')->having('children_count',0)->get();
        foreach($mainAccounts as $account)
        {
            $account->updateHashMap();
        }
        //
    }
}
