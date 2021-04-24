<?php

namespace App\Console\Commands\Item;

use App\Jobs\Items\Google\UpdateGoogleShippingItemJob;
use App\Models\Item;
use Illuminate\Console\Command;

class UpdateGoogleShippingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-shipping:update';

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
        foreach (Item::published()->get() as $item) {
                UpdateGoogleShippingItemJob::dispatch($item);
        }
    }
}
