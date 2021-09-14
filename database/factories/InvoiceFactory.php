<?php

namespace Database\Factories;

use App\Dto\InvoiceDto;
use App\Dto\InvoiceItemDto;
use App\Models\Invoice;
use App\Models\InvoiceItems;
use App\Models\Manager;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;




    public function setDto(InvoiceDto $invoiceDto): InvoiceFactory
    {
        return $this->state(function () use ($invoiceDto) {
            return [
                'organization_id' => $invoiceDto->getManager()->organization_id,
                'branch_id' => $invoiceDto->getManager()->branch_id,
                'department_id' => $invoiceDto->getManager()->department_id,
                'creator_id' => $invoiceDto->getManager()->id,
                'managed_by_id' => $invoiceDto->getManager()->id,
                'is_draft' => $invoiceDto->isDraft(),
                'is_online' => $invoiceDto->isOnline(),
                'user_id' => $invoiceDto->getUser()->id,
                'invoice_type' => $invoiceDto->getInvoiceType()
            ];
        });
    }

    public static function clone(Invoice $baseInvoice): Invoice
    {
        $baseInvoice->load('user', 'creator', 'items.item');
        $mapItems = $baseInvoice->items()->get()->map(function (InvoiceItems $invoiceItem) {
            return [
                'id' => $invoiceItem->item->id,
                'quantity' => $invoiceItem->qty,
                'price' => $invoiceItem->price,
                'discount' => $invoiceItem->discount,
            ];
        });
        $invoiceDto = new InvoiceDto(
            $baseInvoice->creator,
            $baseInvoice->user,
            $baseInvoice->invoice_type,
            $mapItems,
            $baseInvoice->is_draft,
            $baseInvoice->is_online,
        );
        return Invoice::factory()->setDto($invoiceDto)->create();
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'invoice_type' => null,
            'organization_id' => null,
            'branch_id' => null,
            'department_id' => null,
            'user_id' => null,
            'managed_by_id' => null,
            'creator_id' => null,
            'is_online' => false,
            'is_draft' => false,
        ];
    }

    public function configure(): InvoiceFactory
    {
        return $this->afterMaking(function (Invoice $invoice) {
            return $invoice;
        })->afterCreating(function (Invoice $invoice) {
            $invoice->update(['invoice_number' => $this->getInvoiceNumber($invoice)]);
            return $invoice;
        });
    }

    private function getInvoiceNumber(Invoice $invoice): string
    {
        return Str::substr(Str::upper($invoice->invoice_type), 0, 2) . Carbon::now()->format("Y") . $invoice->id;
    }

    /**
     * @param User $user
     * @return InvoiceFactory
     */
    public function setUser(User $user): InvoiceFactory
    {
        return $this->state(function () use ($user) {
            return [
                'user_id' => $user->id,
            ];
        });
    }

    /**
     * @param Manager $manager
     * @return InvoiceFactory
     */
    public function setManager(Manager $manager): InvoiceFactory
    {
        return $this->state(function () use ($manager) {
            return [
                'organization_id' => $manager->organization_id,
                'branch_id' => $manager->branch_id,
                'department_id' => $manager->department_id,
                'creator_id' => $manager->id,
                'managed_by_id' => $manager->id,
            ];
        });
    }


}
