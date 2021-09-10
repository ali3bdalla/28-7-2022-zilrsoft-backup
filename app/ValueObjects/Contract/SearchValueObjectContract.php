<?php

namespace App\ValueObjects\Contract;

use Illuminate\Database\Eloquent\Builder;
interface SearchValueObjectContract
{
    public function applyToQueryBuilder(Builder $builder): Builder;
}
