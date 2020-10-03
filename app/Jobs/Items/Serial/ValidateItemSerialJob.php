<?php

namespace App\Jobs\Items\Serial;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Validation\ValidationException;

class ValidateItemSerialJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $item, $serial, $notAllowedValues;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Item $item, $serial, $notAllowedValues = [])
    {
        $this->serial = $serial;
        $this->notAllowedValues = $notAllowedValues;
        $this->item = $item;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $serial = $this->item->serials()->where('serial',$this->serial)->whereIn('status', $this->notAllowedValues)->first();
        if ($serial) {
            throw ValidationException::withMessages(['item_serial' => 'item serial available with un allowed data']);
        }
    }
}
