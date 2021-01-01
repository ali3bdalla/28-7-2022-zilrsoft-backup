<?php

namespace App\Jobs\Organization\Configurations;

use App\Models\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InitOrganizationYearCloseConfigurationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $loggedUser;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Manager $loggedUser)
    {
        $this->loggedUser = $loggedUser;
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $oganization = $this->loggedUser->organization;
        $oganization->addConfig(true, 'IS_HAS_YEAR_CLOSING', null, 'boolean', 'ACCOUNTING');
        $oganization->addConfig(106, 'TARGET_INCOMES_EXPENSES_NORMALIZATION_ACCOUNT', null, 'integer', 'ACCOUNTING');
        $this->loggedUser->addConfig("2018", 'START_YEAR_AT', null, 'date', 'ACCOUNTING');
        $this->loggedUser->addConfig("2021", 'END_YEAR_AT', null, 'date', 'ACCOUNTING');
    }
}
