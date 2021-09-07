<?php

namespace App\Http\Controllers\App\Web;

use App\Http\Controllers\Controller;
use App\Jobs\Accounting\CloseYear\CreateCloseYearEntityJob;
use App\Jobs\Accounting\CloseYear\NormalizeIncomesExpensesJob;
use App\Jobs\Organization\Configurations\InitOrganizationYearCloseConfigurationJob;
use Illuminate\Http\RedirectResponse;

class PeriodController extends Controller
{


    public function startNormalizingIncomesExpenses(): RedirectResponse
    {

        $manager = auth()->user();
        if (!$manager->organization->getConfig('is_nomalizing_expenses_incomes_running', 'ACCOUNTING')) {
            $manager->organization->addConfig(true, 'is_nomalizing_expenses_incomes_running', null, 'boolean', 'ACCOUNTING');
            NormalizeIncomesExpensesJob::dispatch($manager);
        }

        return back();
    }

    public function close(): RedirectResponse
    {
        CreateCloseYearEntityJob::dispatch(auth()->user());
        return back();
    }

    public function normalizingIncomesExpensesStatus(): array
    {
        $manager = auth()->user();
        $status = $manager->organization->getConfig('is_nomalizing_expenses_incomes_running', 'ACCOUNTING') ? "running" : "not-running";

        $resp = [
            'status' => $status
        ];
        if ($status == 'not-running') {
            $resp['response'] = $manager->organization->getConfig('normalizing_expense_incomes_status', 'ACCOUNTING');
            $resp['error'] = $manager->organization->getConfig('normalizing_expense_incomes_error_message', 'ACCOUNTING');
        }
        return $resp;
    }


    public function initOrganizationConfiguration(): RedirectResponse
    {
        InitOrganizationYearCloseConfigurationJob::dispatchNow(auth()->user());
        return back();
    }

}
