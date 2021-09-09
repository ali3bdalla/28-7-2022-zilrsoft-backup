<?php

namespace App\Http\Controllers\App\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Daily\StoreResellerAccountTransactionRequest;
use App\Http\Requests\Daily\StoreResellerClosingAccountsRequest;
use App\Repository\AccountsDailyRepositoryContract;
use Exception;

class DailyController extends Controller
{

    private AccountsDailyRepositoryContract $accountsDailyRepositoryContract;

    public function __construct(AccountsDailyRepositoryContract $accountsDailyRepositoryContract)
    {
        $this->accountsDailyRepositoryContract = $accountsDailyRepositoryContract;
    }

    /**
     * @throws Exception
     */
    public function storeResellerClosingAccount(StoreResellerClosingAccountsRequest $request)
    {
        return $this->accountsDailyRepositoryContract->createDailyCloseAccountAggregate($request->getBanks());
    }


    /**
     * @throws Exception
     */
    public function storeResellerAccountTransaction(StoreResellerAccountTransactionRequest $request)
    {
        return $request->store();
    }


}
