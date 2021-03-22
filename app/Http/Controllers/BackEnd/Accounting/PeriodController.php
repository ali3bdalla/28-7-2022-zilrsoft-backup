<?php

namespace App\Http\Controllers\BackEnd\Accounting;

use App\Http\Controllers\Controller;
use App\Jobs\Accounting\CloseYear\CreateCloseYearEntityJob;
use App\Jobs\Accounting\CloseYear\NormalizeIncomesExpensesJob;
use App\Jobs\Organization\Configurations\InitOrganizationYearCloseConfigurationJob;
use App\Models\Item;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PeriodController extends Controller
{


    public function startNormalizingIncomesExpenses()
    {

        $manager = auth()->user();
        if (!$manager->organization->getConfig('is_nomalizing_expenses_incomes_running', 'ACCOUNTING')) {
            $manager->organization->addConfig(true, 'is_nomalizing_expenses_incomes_running', null, 'boolean', 'ACCOUNTING');
            NormalizeIncomesExpensesJob::dispatch($manager);
        }

        return back();
    }
    public function close()
    {
        CreateCloseYearEntityJob::dispatch(auth()->user());
        return back();
    }

    public function normalizingIncomesExpensesStatus()
    {
        $manager = auth()->user();
        $status = $manager->organization->getConfig('is_nomalizing_expenses_incomes_running', 'ACCOUNTING') ? "running" : "not-running";

        $resp = [
            'status' => $status
        ];
        if($status == 'not-running')
        {
            $resp['response'] = $manager->organization->getConfig('normalizing_expense_incomes_status', 'ACCOUNTING');
            $resp['error'] = $manager->organization->getConfig('normalizing_expense_incomes_error_message', 'ACCOUNTING');
        }
        return $resp;
    }


    public function initOrganizationConfiguration()
    {
        InitOrganizationYearCloseConfigurationJob::dispatchNow(auth()->user());
        return back();
    }

}
