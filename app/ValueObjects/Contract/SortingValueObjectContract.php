<?php

namespace App\ValueObjects\Contract;

use Illuminate\Database\Eloquent\Builder;

interface SortingValueObjectContract
{
    public function sort(Builder $builder): Builder;

    public function isValid(): bool;
}
