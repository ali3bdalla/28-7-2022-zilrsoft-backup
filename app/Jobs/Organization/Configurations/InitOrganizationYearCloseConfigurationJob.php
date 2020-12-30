<?php

namespace App\Jobs\Organization\Configurations;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InitOrganizationYearCloseConfigurationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oganization = auth()->user()->organization;
        // $content, $key = null, $type = null,$group = ""
        $oganization->addConfig('01/01','year_close_start_at','start date','date',null,'accounting');
        $oganization->addConfig('31/12','year_close_end_atfsd','end date','date',null,'accounting');


        dd($oganization->getConfigurations(null,"year_close_end_atfsd")->toArray());
        // dd($oganization->getConfiguration('accounting_year_close_start_at',true));
        // dd($oganization->getConfig('accounting_year_close_start_at'));
    }
}
