<?php

namespace App\Dto;

use App\Enums\VoucherTypeEnum;
use App\Models\Manager;
use App\Models\User;

class VoucherDto
{
    private Manager $manager;
    private User $user;
    private float $amount;
    private VoucherTypeEnum $type;
    private ?string $description;

    /**
     * @param Manager $manager
     * @param User $user
     * @param float $amount
     * @param VoucherTypeEnum $type
     * @param string|null $description
     */
    public function __construct(Manager $manager, User $user, float $amount, VoucherTypeEnum $type, ?string $description = null)
    {
        $this->user = $user;
        $this->manager = $manager;
        $this->amount = $amount;
        $this->type = $type;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getManagerId(): int
    {
        return $this->manager->id;
    }

    /**
     * @return int
     */
    public function getOrganizationId(): int
    {
        return $this->manager->organization_id;
    }

    /**
     * @return Manager
     */
    public function getManager(): Manager
    {
        return $this->manager;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return VoucherTypeEnum
     */
    public function getType(): VoucherTypeEnum
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }


}
