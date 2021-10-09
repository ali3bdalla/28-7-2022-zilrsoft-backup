<?php

namespace App\Dto;

use App\Base\BaseDtoContract;
use App\Enums\InvoiceTypeEnum;
use App\Models\Invoice;
use App\Models\Item;

class InvoiceItemDto  implements BaseDtoContract
{
    private Item $item;
    private float $quantity;
    private float $price;
    private float $discount;
    private InvoiceTypeEnum $invoiceType;
    private array $serials;
    private ?Invoice $invoice;
    private bool $isKit;
    private int $parentKitId;

    /**
     * @param int $itemId
     * @param InvoiceTypeEnum $invoiceType
     * @param float $quantity
     * @param float $price
     * @param float $discount
     * @param array $serials
     * @param bool $isKit
     * @param int $parentKitId
     */
    public function __construct(int $itemId, InvoiceTypeEnum $invoiceType, float $quantity, float $price = 0, float $discount = 0, array $serials = [], bool $isKit = false, int $parentKitId = 0)
    {
        $this->item = Item::findOrFail($itemId);
        $this->invoiceType = $invoiceType;
        $this->quantity = $quantity;
        $this->price = $price;
        $this->discount = $discount;
        $this->serials = $serials;
        $this->isKit = $isKit;
        $this->parentKitId = $parentKitId;
    }

    /**
     * @return bool
     */
    public function isKit(): bool
    {
        return $this->isKit;
    }

    /**
     * @return int
     */
    public function getParentKitId(): int
    {
        return $this->parentKitId;
    }

    /**
     * @return Invoice|null
     */
    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    /**
     * @param Invoice|null $invoice
     */
    public function setInvoice(?Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }


    /**
     * @return InvoiceTypeEnum
     */
    public function getInvoiceType(): InvoiceTypeEnum
    {
        return $this->invoiceType;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @return array
     */
    public function getSerials(): array
    {
        return $this->serials;
    }

    public function getNet(): float
    {
        return $this->getTax() + $this->getSubtotal();
    }

    public function getTax(): float
    {
        return $this->getSubtotal() * 0.15;
    }

    public function getSubtotal(): float
    {
        return $this->getTotal() - $this->getDiscount();
    }

    public function getTotal(): float
    {
        return $this->getPrice() * $this->getQuantity();
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        if ($this->invoice && $this->invoice->is_online) return $this->item->online_offer_price;
        if ($this->invoiceType->equals(InvoiceTypeEnum::sale()) && $this->item->is_fixed_price)
            return $this->item->price;
        return $this->price;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @return float
     */
    public function getDiscount(): float
    {
        return $this->discount;
    }

}
