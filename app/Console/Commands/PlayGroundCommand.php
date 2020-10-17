<?php

namespace App\Console\Commands;

use App\Models\Account;
use Illuminate\Console\Command;

class PlayGroundCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:play_command';

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
        $account = Account::find(3);
        $snapshot = $account->snapshots()->whereDate('created_at', '2020-10-13')->first();


        dd($snapshot);
    }
}
