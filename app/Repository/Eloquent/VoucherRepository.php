<?php

namespace App\Repository\Eloquent;

use App\Dto\VoucherDto;
use App\Enums\VoucherTypeEnum;
use App\Models\Voucher;
use App\Repository\EntryRepositoryContract;
use App\Repository\VoucherRepositoryContract;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Support\Facades\DB;

class VoucherRepository extends BaseRepository implements VoucherRepositoryContract
{
    private EntryRepositoryContract $entryRepositoryContract;

    public function __construct(
        EntryRepositoryContract $entryRepositoryContract
    )
    {
        $this->entryRepositoryContract = $entryRepositoryContract;
    }

    public function getAmount(SearchValueObjectContract $searchValueObjectContract): float
    {
        $query = Voucher::query();
        $query = $searchValueObjectContract->apply($query);
        return (float)$query->sum('amount');
    }

    public function refundVoucher(Voucher $voucher): Voucher
    {
        return DB::transaction(function () use ($voucher) {
            $refundVoucher = Voucher::factory()->refund($voucher)->create();
            if ($voucher->payment_type->equals(VoucherTypeEnum::receipt())) $this->entryRepositoryContract->registerClientVoucherEntry($refundVoucher, $refundVoucher->account);
            else  $this->entryRepositoryContract->registerVendorVoucherEntry($refundVoucher, $refundVoucher->account);
            $voucher->markAsRefunded();
            return $refundVoucher;
        });
    }

    public function createVoucher(VoucherDto $voucherDto): Voucher
    {
        return DB::transaction(function () use ($voucherDto) {
            $voucher = Voucher::factory()->setDto($voucherDto)->create();
            if ($voucher->payment_type->equals(VoucherTypeEnum::receipt())) $this->entryRepositoryContract->registerClientVoucherEntry($voucher, $voucher->account);
            else  $this->entryRepositoryContract->registerVendorVoucherEntry($voucher, $voucher->account);
            return $voucher;
        });
    }

}
