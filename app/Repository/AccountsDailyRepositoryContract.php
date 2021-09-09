<?php

namespace App\Repository;

use App\Enums\VoucherTypeEnum;

interface AccountsDailyRepositoryContract extends BaseRepositoryContract
{
    public function createDailyCloseAccountAggregate(array $banks = []);

    public function getResellerDailyBankIncomeAmount(bool $excludeVouchers, $accountId = null): float;

    public function getResellerDailyBankOutcomeAmount(bool $excludeVouchers, $accountId = null): float;

    public function getResellerDailyAmount(VoucherTypeEnum $voucherTypeEnum, bool $excludeVouchers, $accountId = null): float;
}
