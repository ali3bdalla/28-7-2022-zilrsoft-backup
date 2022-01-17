<?php

namespace App\Repository;

interface AccountsDailyRepositoryContract extends BaseRepositoryContract
{
    public function closePeriodAccounts(array $banks = []);

    public function getResellerDailyBankIncomeAmount(bool $includeManualVouchers = true, ?int $accountId = null): float;

    public function getResellerDailyBankOutcomeAmount(bool $includeManualVouchers = true, ?int  $accountId = null): float;
}
