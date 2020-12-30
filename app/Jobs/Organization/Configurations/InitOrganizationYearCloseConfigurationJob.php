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
        $oganization->addConfig(true, 'IS_HAS_YEAR_CLOSING', null, 'boolean', 'ACCOUNTING');
        $oganization->addConfig('01/01', 'YEAR_STARTING_AT', 'Starting Year At', 'date', 'ACCOUNTING');
        $oganization->addConfig('31/12', 'YEAR_CLOSEING_AT', 'Closing Year At', 'date', 'ACCOUNTING');
        $oganization->addConfig(106, 'TARGET_INCOMES_EXPENSES_NORMALIZATION_ACCOUNT', null, 'integer', 'ACCOUNTING');
    }
}
