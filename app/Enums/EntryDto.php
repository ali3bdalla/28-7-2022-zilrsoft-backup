<?php

namespace App\Enums;

use App\Models\Account;
use App\Models\Manager;
use Illuminate\Support\Collection;

class EntryDto
{

    private Manager $manager;
    private bool $isPending;
    private string $description;
    private ?int $invoiceId;
    private Collection $transactions;

    /**
     * @param Manager $manager
     * @param bool $isPending
     * @param string $description
     * @param int|null $invoiceId
     * @param Collection $transactions
     */
    public function __construct(Manager $manager, Collection $transactions, bool $isPending = false, string $description = "", ?int $invoiceId = null)
    {
        $this->manager = $manager;
        $this->isPending = $isPending;
        $this->description = $description;
        $this->invoiceId = $invoiceId;
        $this->transactions = $transactions;
    }

    public function setTransactionsFromArray(array $transactions)
    {
        $collection = new Collection();
        foreach ($transactions as $transaction) {
            $transaction = collect($transaction);
            $collection->add(new TransactionDto(
                Account::findOrFail($transaction->get('account_id')),
                AccountingTypeEnum::from($transaction->get('type')),
                (float)$transaction->get('amount', 0),
                (bool)$transaction->get('is_pending', false),
                (bool)$transaction->get('is_manual', false),
                (int)$transaction->get('item_id'),
                (int)$transaction->get('invoice_id'),
                (int)$transaction->get('user_id'),
            ));
        }
        $this->transactions = $collection;
    }

    /**
     * @return int
     */
    public function getOrganizationId(): int
    {
        return $this->manager->organization_id;
    }


    /**
     * @return int
     */
    public function getManagerId(): int
    {
        return $this->manager->id;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->isPending;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int|null
     */
    public function getInvoiceId(): ?int
    {
        return $this->invoiceId;
    }

    /**
     * @return Collection
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function getAmount(): float
    {
        return (float)$this->transactions->reduce(function ($amount, TransactionDto $transactionDto) {
            if ($transactionDto->getType()->equals(AccountingTypeEnum::debit()))
                return $amount + $transactionDto->getAmount();
            return $amount;
        }, 0);
    }

}
