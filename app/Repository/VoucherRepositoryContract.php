<?php

namespace App\Repository;

use App\ValueObjects\Contract\SearchValueObjectContract;

interface VoucherRepositoryContract extends BaseRepositoryContract
{
    public function getAmount(SearchValueObjectContract $searchValueObjectContract): float;
}
