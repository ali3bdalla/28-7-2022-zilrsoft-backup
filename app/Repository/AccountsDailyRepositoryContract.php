<?php

namespace App\Repository;

interface AccountsDailyRepositoryContract extends BaseRepositoryContract
{
    public function closePeriodAccounts(array $banks = []);

    public function getResellerDailyBankIncomeAmount(bool $excludeVouchers, ?int $accountId = null): float;

    public function getResellerDailyBankOutcomeAmount(bool $excludeVouchers,?int  $accountId = null): float;

}
