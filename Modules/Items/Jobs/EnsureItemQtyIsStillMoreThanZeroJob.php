<?php

namespace Modules\Items\Jobs;

use App\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnsureItemQtyIsStillMoreThanZeroJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var Item
     */
    private $item;
    private $index;

    /**
     * Create a new job instance.
     *
     * @param Item $item
     * @param $index
     */
    public function __construct(Item $item,$index = 0)
    {
        //
        $this->item = $item;
        $this->index = $index;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if($this->item->isQtyable() && $this->item->available_qty < 0)
        {
            $error = \Illuminate\Validation\ValidationException::withMessages([
                "items.{$this->index}.qty"=> ['item qty can\'t be less than zero '],
            ]);
            throw $error;
        }
    }
}
