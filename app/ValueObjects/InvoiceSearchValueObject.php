<?php

namespace App\ValueObjects;

use App\Enums\AccountingTypeEnum;
use App\Scopes\DraftScope;
use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class InvoiceSearchValueObject implements SearchValueObjectContract
{
    private ?AccountingTypeEnum $invoiceTypeEnum;
    private ?bool $isDraft;
    private ?Carbon $startAt;
    private ?Carbon $endAt;
    private ?array $creators;
    private ?array $clients;
    private ?array $salesMen;
    private ?string $aliceName;
    private ?string $title;
    private ?float $net;
    private ?float $tax;
    private ?float $total;
    private ?float $discount;
    private ?float $subtotal;

    /**
     * @param AccountingTypeEnum|null $invoiceTypeEnum
     * @param bool|null $isDraft
     * @param Carbon|null $startAt
     * @param Carbon|null $endAt
     * @param array|null $creators
     * @param array|null $clients
     * @param array|null $salesMen
     * @param string|null $aliceName
     * @param string|null $title
     * @param float|null $net
     * @param float|null $tax
     * @param float|null $total
     * @param float|null $discount
     * @param float|null $subtotal
     */
    public function __construct(?AccountingTypeEnum $invoiceTypeEnum, ?bool $isDraft, ?Carbon $startAt, ?Carbon $endAt, ?array $creators, ?array $clients, ?array $salesMen, ?string $aliceName, ?string $title, ?float $net, ?float $tax, ?float $total, ?float $discount, ?float $subtotal)
    {
        $this->invoiceTypeEnum = $invoiceTypeEnum;
        $this->isDraft = $isDraft;
        $this->startAt = $startAt;
        $this->endAt = $endAt;
        $this->creators = $creators;
        $this->clients = $clients;
        $this->salesMen = $salesMen;
        $this->aliceName = $aliceName;
        $this->title = $title;
        $this->net = $net;
        $this->tax = $tax;
        $this->total = $total;
        $this->discount = $discount;
        $this->subtotal = $subtotal;
    }




    public function apply(Builder $builder): Builder
    {
        if ($this->getStartAt()) $builder->where('created_at', '>=', $this->getStartAt());
        if ($this->getEndAt()) $builder->where('created_at', '<=', $this->getEndAt());
        if ($this->getAliceName()) $builder->where('alice_name', 'iLike', "%{$this->getAliceName()}%");
        if ($this->getTitle()) $builder->where('invoice_number', 'iLike', "%{$this->getTitle()}%");
        if ($this->getNet()) $builder->whereNet($this->getNet());
        if ($this->getTax()) $builder->whereTax($this->getTax());
        if ($this->getTotal()) $builder->whereTotal($this->getTotal());
        if ($this->getSubtotal()) $builder->whereSubtotal($this->getSubtotal());
        if ($this->getDiscount()) $builder->whereDiscount($this->getDiscount());
        if ($this->getInvoiceTypeEnum()) $builder->whereInvoiceType($this->getInvoiceTypeEnum());
        if ($this->isDraft()) $builder->whereIsDraft(true)->withoutGloablScope(DraftScope::class);
        if ($this->getCreators() && !empty($this->getCreators())) $builder->whereIn('creator_id', $this->getCreators());
        if ($this->getClients() && !empty($this->getClients())) $builder->whereIn('user_id', $this->getClients());
        if ($this->getSalesMen() && !empty($this->getSalesMen())) $builder->where('salesman_id', $this->getSalesMen());
        return $builder;
    }

    /**
     * @return Carbon|null
     */
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

    /**
     * @return Carbon|null
     */
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

    /**
     * @return string|null
     */
    public function getAliceName(): ?string
    {
        return $this->aliceName;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return float|null
     */
    public function getNet(): ?float
    {
        return $this->net;
    }

    /**
     * @return float|null
     */
    public function getTax(): ?float
    {
        return $this->tax;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @return float|null
     */
    public function getSubtotal(): ?float
    {
        return $this->subtotal;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @return AccountingTypeEnum|null
     */
    public function getInvoiceTypeEnum(): ?AccountingTypeEnum
    {
        return $this->invoiceTypeEnum;
    }

    /**
     * @return bool|null
     */
    public function isDraft(): ?bool
    {
        return $this->isDraft;
    }

    /**
     * @return array|null
     */
    public function getCreators(): ?array
    {
        return $this->creators;
    }

    /**
     * @return array|null
     */
    public function getClients(): ?array
    {
        return $this->clients;
    }

    /**
     * @return array|null
     */
    public function getSalesMen(): ?array
    {
        return $this->salesMen;
    }

}
