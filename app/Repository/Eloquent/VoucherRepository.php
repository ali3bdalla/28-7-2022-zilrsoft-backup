<?php

namespace App\Repository\Eloquent;

use App\Dto\VoucherDto;
use App\Models\Payment;
use App\Repository\VoucherRepositoryContract;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Support\Facades\DB;

class VoucherRepository extends BaseRepository implements VoucherRepositoryContract
{

    public function getAmount(SearchValueObjectContract $searchValueObjectContract): float
    {
        $query = Payment::query();
        $query = $searchValueObjectContract->apply($query);
        return (float)$query->sum('amount');
    }

    public function createVoucher(VoucherDto $voucherDto): Payment
    {
        return DB::transaction(function () use ($voucherDto) {
            return Payment::factory()->setDto($voucherDto)->create();
        });
    }
}
