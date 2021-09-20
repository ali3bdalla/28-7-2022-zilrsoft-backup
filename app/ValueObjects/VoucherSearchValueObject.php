<?php

namespace App\ValueObjects;

use App\Enums\VoucherTypeEnum;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class VoucherSearchValueObject implements SearchValueObjectContract
{

    private bool $includeInvoicesVouchers;
    private bool $includeManualVouchers;
    private ?int $accountId;
    private ?VoucherTypeEnum $voucherType;
    private ?Carbon $startAt;
    private ?Carbon $endAt;

    /**
     * @param bool $includeInvoicesVouchers
     * @param bool $includeManualVouchers
     * @param int|null $accountId
     * @param VoucherTypeEnum|null $voucherType
     * @param Carbon|null $startAt
     * @param Carbon|null $endAt
     */
    public function __construct(bool $includeInvoicesVouchers = true, bool $includeManualVouchers = true, ?int $accountId = null, ?VoucherTypeEnum $voucherType = null, ?Carbon $startAt = null, ?Carbon $endAt = null)
    {
        $this->includeInvoicesVouchers = $includeInvoicesVouchers;
        $this->includeManualVouchers = $includeManualVouchers;
        $this->accountId = $accountId;
        $this->voucherType = $voucherType;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
    }

    public function apply(Builder $builder): Builder
    {
        if ($this->includeInvoicesVouchers === false) $builder->whereNull('invoice_id');
        if ($this->includeManualVouchers === false ) $builder->whereNotNull('invoice_id');
        if ($this->accountId !== null) $builder->whereAccountId($this->accountId);
        if ($this->voucherType !== null) $builder->wherePaymentType($this->voucherType);
        if ($this->startAt !== null) $builder->where(fn(Builder  $query) => $query->whereDate('created_at', '>=', $this->startAt)->whereTime('created_at', '>=', $this->startAt->toTimeString()));
        if ($this->endAt !== null) $builder->where(fn(Builder  $query) => $query->whereDate('created_at', '<=', $this->endAt)->whereTime('created_at', '<=', $this->endAt->toTimeString()));
        return $builder;
    }
}
