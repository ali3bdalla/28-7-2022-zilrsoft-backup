<?php

namespace App\Dto;

use App\Enums\AccountingTypeEnum;
use App\Enums\AccountSlugEnum;
use App\Models\Manager;

class AccountDto
{
    private Manager $manager;
    private string $name;
    private string $arName;
    private AccountingTypeEnum $type;
    private int $parentId;
    private bool $isSystemAccount;
    private bool $isGateway;
    private ?AccountSlugEnum $accountSlug;

    /**
     * @param Manager $manager
     * @param string $name
     * @param string $arName
     * @param AccountingTypeEnum $type
     * @param int $parentId
     * @param bool $isSystemAccount
     * @param bool $isGateway
     */
    public function __construct(Manager $manager, string $name, string $arName, AccountingTypeEnum $type, ?AccountSlugEnum $accountSlug = null, int $parentId = 0, bool $isSystemAccount = false, bool $isGateway = false)
    {
        $this->manager = $manager;
        $this->name = $name;
        $this->arName = $arName;
        $this->type = $type;
        $this->parentId = $parentId;
        $this->isSystemAccount = $isSystemAccount;
        $this->isGateway = $isGateway;
        $this->accountSlug = $accountSlug;
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getArName(): string
    {
        return $this->arName;
    }

    /**
     * @return AccountingTypeEnum
     */
    public function getType(): AccountingTypeEnum
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parentId;
    }

    /**
     * @return bool
     */
    public function isSystemAccount(): bool
    {
        return $this->isSystemAccount;
    }

    /**
     * @return bool
     */
    public function isGateway(): bool
    {
        return $this->isGateway;
    }

    /**
     * @return AccountSlugEnum|null
     */
    public function getAccountSlug(): ?AccountSlugEnum
    {
        return $this->accountSlug;
    }


}
