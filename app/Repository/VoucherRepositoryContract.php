<?php

namespace App\Repository;

use App\Dto\VoucherDto;
use App\Enums\VoucherTypeEnum;
use App\Models\Payment;
use App\ValueObjects\Contract\SearchValueObjectContract;

interface VoucherRepositoryContract extends BaseRepositoryContract
{
    public function getAmount(SearchValueObjectContract $searchValueObjectContract): float;

    public function createVoucher(VoucherDto $voucherDto): Payment;
}
