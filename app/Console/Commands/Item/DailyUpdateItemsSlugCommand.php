<?php

namespace App\Console\Commands\Item;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class DailyUpdateItemsSlugCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:DailyUpdateItemsSlugCommand';

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
        Item::query()->update([
            'en_slug' => null,
            'ar_slug' => null,
        ]);
        DB::transaction(function(){
            Item::query()->each(function($item) {
                $item->update([
                    'en_slug' => Str::slug("$item->name $item->id"),
                    'ar_slug' => Str::slug("$item->ar_name $item->id",'ar'),
                ]);
            });
        });


    }
}
