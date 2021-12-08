<?php

namespace App\Repository;

use Illuminate\Support\Carbon;

interface InventoryRepositoryContract extends BaseRepositoryContract
{

    public function createAdjustment(array $items);
}
