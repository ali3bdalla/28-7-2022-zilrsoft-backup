<?php

namespace Database\Factories;

use App\Dto\InvoiceItemDto;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceItems::class;


    /**
     * @param Invoice $invoice
     * @return InvoiceItemsFactory
     */
    public function setInvoice(Invoice $invoice): InvoiceItemsFactory
    {
        return $this->state(function () use ($invoice) {
            return [
                "organization_id" => $invoice->organization_id,
                "creator_id" => $invoice->creator_id,
                "user_id" => $invoice->user_id,
                "invoice_id" => $invoice->id,
                "is_draft" => $invoice->is_draft,
                "invoice_type" => $invoice->invoice_type
            ];
        });
    }

    /**
     * @param InvoiceItemDto $invoiceItemDto
     * @return InvoiceItemsFactory
     */
    public function setDto(InvoiceItemDto $invoiceItemDto): InvoiceItemsFactory
    {
        return $this->state(function () use ($invoiceItemDto) {
            return [
                'qty' => $invoiceItemDto->getQuantity(),
                "organization_id" => $invoiceItemDto->getInvoice()->organization_id,
                "creator_id" => $invoiceItemDto->getInvoice()->creator_id,
                "user_id" => $invoiceItemDto->getInvoice()->user_id,
                "invoice_id" => $invoiceItemDto->getInvoice()->id,
                "is_draft" => $invoiceItemDto->getInvoice()->is_draft,
                "invoice_type" => $invoiceItemDto->getInvoice()->invoice_type,
                "item_id" => $invoiceItemDto->getItem()->id,
                'price' => $invoiceItemDto->getPrice(),
                'total' => $invoiceItemDto->getTotal(),
                'tax' => $invoiceItemDto->getTax(),
                'subtotal' => $invoiceItemDto->getSubtotal(),
                'net' => $invoiceItemDto->getNet(),
                'discount' => $invoiceItemDto->getDiscount(),
                "belong_to_kit" => (bool)$invoiceItemDto->getParentKitId(),
                "parent_kit_id" => $invoiceItemDto->getParentKitId(),
                'is_kit' => $invoiceItemDto->isKit()
            ];
        });
    }


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            "is_kit" => false,
            "belong_to_kit" => false,
            "parent_kit_id" => 0,
            "discount" => 0,
            "price" => 0,
            "qty" => 0,
            "total" => 0,
            "subtotal" => 0,
            "net" => 0,
            "organization_id" => null,
            "item_id" => null,
            "user_id" => null,
            "creator_id" => null,
            "invoice_type" => null,
            "is_draft" => false,
        ];
    }

    public function configure(): InvoiceItemsFactory
    {
        return $this->afterMaking(function (InvoiceItems $invoiceItem) {
            return $invoiceItem;
        });
    }


}
