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
        $items = Item::where([
            ['organization_id', 1],
            ['is_available_online', true],
            ['is_category_available_online', true],
            ['is_kit', false],
        ])->whereNotIn('barcode', ["194441597332", "6986500195870", "193905621590", "190781167747", "718037868448", "740617249231", "6901443368907", "5530324215022", "4710886126423", "6446611318679"])->get();
        foreach ($items as $item) {
            if ($item->shouldBeSearchable())
                UpdateGoogleShippingItemJob::dispatch($item);
        }
    }
}
