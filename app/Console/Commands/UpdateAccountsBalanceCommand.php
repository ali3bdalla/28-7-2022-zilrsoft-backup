<?php

namespace App\Console\Commands;

use App\Account;
use Illuminate\Console\Command;

class UpdateAccountsBalanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_accounts_balance';

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
        $accounts = Account::all();
        foreach ($accounts as $account)
        {
            $account->_updateBalanceUsingTransactions();
        }

    }
}
