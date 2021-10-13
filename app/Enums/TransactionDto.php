<?php

namespace App\Enums;

use App\Models\Account;
use App\Models\Manager;
use App\Models\Entry;

class TransactionDto
{
    private Account $account;
    private AccountingTypeEnum $type;
    private float $amount;
    private bool $isPending;
    private ?int $itemId;
    private ?int $invoiceId;
    private ?int $userId;
    private bool $isManual;

    /**
     * @param Account $account
     * @param AccountingTypeEnum $type
     * @param float $amount
     * @param bool $isPending
     * @param int|null $itemId
     * @param int|null $invoiceId
     * @param int|null $userId
     * @param bool $isManual
     */
    public function __construct(Account $account, AccountingTypeEnum $type, float $amount, bool $isPending = false, bool $isManual = false, ?int $itemId = null, ?int $invoiceId = null, ?int $userId = null)
    {
        $this->account = $account;
        $this->type = $type;
        $this->amount = $amount;
        $this->isPending = $isPending;
        $this->itemId = $itemId;
        $this->invoiceId = $invoiceId;
        $this->userId = $userId;
        $this->isManual = $isManual;
    }


    /**
     * @return Account
     */
    public function getAccount(): Account
    {
        return $this->account;
    }
    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->account->id;
    }

    /**
     * @return AccountingTypeEnum
     */
    public function getType(): AccountingTypeEnum
    {
        return $this->type;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->isPending;
    }

    /**
     * @return int|null
     */
    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    /**
     * @return int|null
     */
    public function getInvoiceId(): ?int
    {
        return $this->invoiceId;
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * @return bool
     */
    public function isManual(): bool
    {
        return $this->isManual;
    }

}
