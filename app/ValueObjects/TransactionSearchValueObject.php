<?php

namespace App\ValueObjects;

use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class TransactionSearchValueObject implements SearchValueObjectContract
{
    private ?int $userId;
    private ?int $invoiceId;
    private ?int $itemId;
    private ?MoneyValueObject $moneyValueObject;
    private ?Carbon $startAt;
    private ?Carbon $endAt;

    public function __construct(?MoneyValueObject $moneyValueObject = null, ?int $userId = null, ?int $invoiceId = null, ?int $itemId = null, ?string $startAt = null, ?string $endAt = null)
    {
        $this->moneyValueObject = $moneyValueObject;
        $this->userId = $userId;
        $this->invoiceId = $invoiceId;
        $this->itemId = $itemId;
        $this->setStartAt($startAt);
        $this->setEndAt($endAt);
    }

    public function apply(Builder $builder): Builder
    {
        if ($this->hasMoney())
            $builder->whereAmount($this->getMoney()->getAmount());
        if ($this->hasStartAt())
            $builder->where('created_at', '>=', $this->getStartAt());
        if ($this->hasEndAt())
            $builder->where('created_at', '<=', $this->getEndAt());
        if ($this->hasUserId())
            $builder->whereUserId($this->getUserId());
        if ($this->hasItemId())
            $builder->whereItemId($this->getItemId());
        if ($this->hasInvoiceId())
            $builder->whereInoviceId($this->getInvoiceId());
        return $builder;
    }

    public function hasMoney(): bool
    {
        return $this->moneyValueObject && $this->moneyValueObject->isValid();
    }


    public function getMoney(): ?MoneyValueObject
    {
        return $this->moneyValueObject;
    }

    public function hasStartAt(): bool
    {
        return $this->startAt != null;
    }


    public function getStartAt(): ?Carbon
    {
        return $this->startAt;
    }


    public function setStartAt(?string $startAt): void
    {
        if ($startAt)
            $this->startAt = Carbon::parse($startAt);
        else
            $this->startAt = null;
    }

    public function hasEndAt(): bool
    {
        return $this->endAt != null;
    }

    public function getEndAt(): ?Carbon
    {
        return $this->endAt;
    }

    public function setEndAt(?string $endAt): void
    {
        if ($endAt)
            $this->endAt = Carbon::parse($endAt);
        else
            $this->endAt = null;
    }

    public function hasUserId(): bool
    {
        return $this->userId != null;
    }


    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function hasItemId(): bool
    {
        return $this->itemId != null;
    }
    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function hasInvoiceId(): bool
    {
        return $this->invoiceId != null;
    }
    public function getInvoiceId(): ?int
    {
        return $this->invoiceId;
    }


}
