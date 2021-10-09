<?php

namespace App\Dto;

use App\Base\BaseDtoContract;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\ShippingAddress;
use App\Models\ShippingMethod;
use App\Models\User;
use App\Scopes\DraftScope;
use Illuminate\Support\Collection;

class OrderDto  implements BaseDtoContract
{
    private InvoiceDto $invoiceDto;
    private ?Invoice $draftInvoice;
    private User $user;
    private ?ShippingAddress $shippingAddress;
    private ?string $paymentMethodId;
    private ?ShippingMethod $shippingMethod;
    private Collection $orderItems;

    /**
     * @param InvoiceDto $invoiceDto
     * @param ShippingMethod|null $shippingMethod
     * @param ShippingAddress|null $shippingAddress
     * @param string|null $paymentMethodId
     */
    public function __construct(InvoiceDto $invoiceDto, ?ShippingMethod $shippingMethod = null, ?ShippingAddress $shippingAddress = null, ?string $paymentMethodId = null)
    {

        $this->user = $invoiceDto->getUser();
        $this->invoiceDto = $invoiceDto;
        $this->shippingAddress = $shippingAddress;
        $this->paymentMethodId = $paymentMethodId;
        $this->shippingMethod = $shippingMethod;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user->id;
    }

    public function getShippingCost(): float
    {
        if (!$this->shippingMethod) return 0;
        return $this->shippingMethod->getShippingCost($this->getShippingWeight());
    }

    public function getShippingWeight(): float
    {
        return $this->orderItems->reduce(function ($result, InvoiceItems $invoiceItem, $key) {
            return $result + (float)($invoiceItem->item->weight * $invoiceItem->qty);
        }, 0);
    }

    public function getOrderNet(): float
    {
        $invoiceAmount = $this->draftInvoice->net;
        $shippingAmount = $this->getShippingAmount();
        return $invoiceAmount + $shippingAmount;
    }

    public function getShippingAmount(): float
    {
        if ($this->shippingMethod === null) return 0;
        $shippingWeight = $this->getShippingWeight();
        $shippingDiscount = $this->getShippingDiscount();
        return $this->shippingMethod->getShippingAmount($shippingWeight, $shippingDiscount);
    }

    public function getShippingDiscount(): float
    {
        return $this->orderItems->reduce(function ($result, InvoiceItems $invoiceItem, $key) {
            return $result + (float)($invoiceItem->item->shipping_discount * $invoiceItem->qty);
        }, 0);
    }

    /**
     * @return Invoice
     */
    public function getDraftInvoice(): Invoice
    {
        return $this->draftInvoice;
    }

    /**
     * @param Invoice|null $draftInvoice
     */
    public function setDraftInvoice(?Invoice $draftInvoice): void
    {
        $this->draftInvoice = $draftInvoice;
        $this->orderItems = $draftInvoice->items()->with('item')->withoutGlobalScope(DraftScope::class)->get();
    }

    /**
     * @return int|null
     */
    public function getShippingAddressId(): ?int
    {
        return $this->shippingAddress ? $this->shippingAddress->id : null;
    }

    /**
     * @return ShippingAddress|null
     */
    public function getShippingAddress(): ?ShippingAddress
    {
        return $this->shippingAddress;
    }

    /**
     * @return string|null
     */
    public function getPaymentMethodId(): ?string
    {
        return $this->paymentMethodId;
    }

    /**
     * @return int|null
     */
    public function getShippingMethodId(): ?int
    {
        return $this->shippingMethod ? $this->shippingMethod->id : null;
    }

    /**
     * @return ShippingMethod|null
     */
    public function getShippingMethod(): ?ShippingMethod
    {
        return $this->shippingMethod;
    }

    public function getOrganizationId(): int
    {
        return $this->draftInvoice->organization_id;
    }

    public function getDraftInvoiceId(): int
    {
        return $this->draftInvoice->id;
    }

    /**
     * @return InvoiceDto
     */
    public function getInvoiceDto(): InvoiceDto
    {
        return $this->invoiceDto;
    }


}
