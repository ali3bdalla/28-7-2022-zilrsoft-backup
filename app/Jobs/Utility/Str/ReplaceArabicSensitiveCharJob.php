<?php

namespace App\Jobs\Utility\Str;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReplaceArabicSensitiveCharJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $text;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($text = "")
    {
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle(): string
    {
        $searchArray = explode(' ', $this->text);


        $result = [];

        foreach ($searchArray as $searchKey) {
            $str = str_replace(['أ', 'إ', 'آ'], 'ا', $searchKey);
            // $str = str_replace( '/ه$/','ة',$str);
            $str = preg_replace('/ه$/', 'ة', $str);

            $result[] = $str;
        }


        return implode(' ', $result);


    }
}
