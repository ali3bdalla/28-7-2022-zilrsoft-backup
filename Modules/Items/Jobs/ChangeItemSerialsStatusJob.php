<?php

namespace Modules\Items\Jobs;

use App\Item;
use App\ItemSerials;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ChangeItemSerialsStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Item
     */
    private $item;
    private $serials;
    /**
     * @var string
     */
    /**
     * @var array
     */
    private $searchByStatuses;
    /**
     * @var string
     */
    private $changeTo;

    /**
     * Create a new job instance.
     *
     * @param Item $item
     * @param $serials
     * @param array $searchByStatuses
     * @param string $changeTo
     */
    public function __construct(Item $item,$serials,$searchByStatuses = [], $changeTo = 'saled')
    {
        //
        $this->item = $item;
        $this->serials =(array) $serials;
        $this->searchByStatuses = $searchByStatuses;
        $this->changeTo = $changeTo;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        foreach ($this->serials as $key => $serial)
        {
            ItemSerials::where([['serial', $serial]])->whereIn('current_status',$this->searchByStatuses)->first()->update([
                'current_status' => $this->changeTo
            ]);
        }

    }
}
