<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Collection;

interface ManagerRepositoryContract extends BaseRepositoryContract
{
    public function getCurrentManagerBanks(): Collection;

    public function getAllManagersBanksExcept(array $managersId): array;
}
