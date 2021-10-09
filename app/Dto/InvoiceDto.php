<?php

namespace App\Dto;

use App\Base\BaseDtoContract;
use App\Enums\InvoiceTypeEnum;
use App\Models\Manager;
use App\Models\User;
use Illuminate\Support\Collection;

class InvoiceDto  implements BaseDtoContract
{
    private Manager $manager;
    private User $user;
    private InvoiceTypeEnum $invoiceType;
    private bool $isDraft;
    private bool $isOnline;
    private array $items;

    /**
     * @param Manager $manager
     * @param User $user
     * @param InvoiceTypeEnum $invoiceType
     * @param array $items
     * @param bool $isDraft
     * @param bool $isOnline
     */
    public function __construct(Manager $manager, User $user, InvoiceTypeEnum $invoiceType, array $items, bool $isDraft = false, bool $isOnline = false)
    {
        $this->manager = $manager;
        $this->user = $user;
        $this->invoiceType = $invoiceType;
        $this->items = $items;
        $this->isDraft = $isDraft;
        $this->isOnline = $isOnline;
    }

    /**
     * @return Collection
     */
    public function getItems(): Collection
    {
        return collect($this->items)->map(function ($item) {
            $item = collect($item);
            return new InvoiceItemDto(
                (int)$item->get('id', 0),
                $this->invoiceType,
                (float)$item->get('quantity', 0),
                (float)$item->get('price', 0),
                (float)$item->get('discount', 0),
                (array)$item->get('serials', []),
            );
        });
    }

    /**
     * @return Manager
     */
    public function getManager(): Manager
    {
        return $this->manager;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return InvoiceTypeEnum
     */
    public function getInvoiceType(): InvoiceTypeEnum
    {
        return $this->invoiceType;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->isOnline;
    }


}
