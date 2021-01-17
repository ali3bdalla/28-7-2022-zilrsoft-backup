<?php

namespace App\Jobs\External\Smsa;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;



class SmsaGetRtlCitiesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        try {
            $result = app('SmsaClient')->getRTLCities();
            $data = simplexml_load_string($result->body()->getRTLCitiesResult->any);
            return $data;
        } catch (\Throwable $th) {
            return [];
        }
    }
}
