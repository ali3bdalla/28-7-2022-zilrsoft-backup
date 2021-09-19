<?php

namespace App\Repository\Eloquent;

use App\Models\Payment;
use App\Repository\VoucherRepositoryContract;
use App\ValueObjects\Contract\SearchValueObjectContract;

class VoucherRepository extends BaseRepository implements VoucherRepositoryContract
{

    public function getAmount(SearchValueObjectContract $searchValueObjectContract): float
    {
        $query = Payment::query();
        $query = $searchValueObjectContract->apply($query);
        return (float)$query->sum('amount');
    }
}
