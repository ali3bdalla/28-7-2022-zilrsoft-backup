<?php

namespace App\Jobs\Items\Tag;

use App\Jobs\Utility\Str\ReplaceArabicSensitiveCharJob;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateItemTagsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tags,$item;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Item $item,$tags =[])
    {
        $this->item = $item;
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $itemsTags = $this->item->tags()->pluck('tag')->toArray();
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                if(!in_array($tag,$itemsTags))
                {
                    $this->item->tags()->create([
                        'tag' =>  $tag,//ReplaceArabicSensitiveCharJob::dispatchSync($tag)
                    ]);
                }

                
            }
        }
        $this->item->tags()->whereNotIn('tag',$this->tags)->delete();

        $tags = [];
        foreach ($this->item->tags()->get() as $tag) {
            # code...
            if(in_array($tag->tag,$tags)) {
                $tag->delete();
            }else {
                $tags[] = $tag->tag;
            }
        }
    }
}
