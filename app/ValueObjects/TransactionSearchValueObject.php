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
        if ($this->hasStartAt() && $this->hasEndAt())
            $builder->whereBetween('created_at', [$this->getStartAt(), $this->getEndAt()]);
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

    public function hasEndAt(): bool
    {
        return $this->endAt != null;
    }

    /**
     * @return ?Carbon
     */
    public function getStartAt(): ?Carbon
    {
        return $this->startAt;
    }

    /**
     * @param Carbon|string|null $startAt
     */
    public function setStartAt(?string $startAt): void
    {
        if ($startAt)
            $this->startAt = Carbon::parse($startAt);
        else
            $this->startAt = null;
    }

    /**
     * @return ?Carbon
     */
    public function getEndAt(): ?Carbon
    {
        return $this->endAt;
    }

    /**
     * @param Carbon|string|null $endAt
     */
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

    /**
     * @return ?int
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function hasItemId(): bool
    {
        return $this->itemId != null;
    }

    /**
     * @return ?int
     */
    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function hasInvoiceId(): bool
    {
        return $this->invoiceId != null;
    }

    /**
     * @return ?int
     */
    public function getInvoiceId(): ?int
    {
        return $this->invoiceId;
    }


}
