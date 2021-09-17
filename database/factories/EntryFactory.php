<?php

namespace Database\Factories;

use App\Enums\EntryDto;
use App\Models\TransactionsContainer;
use Illuminate\Database\Eloquent\Factories\Factory;

class EntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TransactionsContainer::class;

    public function setDto(EntryDto $entryDto): EntryFactory
    {
        return $this->state(function () use ($entryDto) {
            return [
                'organization_id' => $entryDto->getOrganizationId(),
                'creator_id' => $entryDto->getManagerId(),
                'amount' => $entryDto->getAmount(),
                'description' => $entryDto->getDescription(),
                'is_pending' => $entryDto->isPending(),
                'invoice_id' => $entryDto->getInvoiceId(),
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
