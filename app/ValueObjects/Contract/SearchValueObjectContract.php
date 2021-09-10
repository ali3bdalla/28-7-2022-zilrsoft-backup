<?php

namespace App\ValueObjects\Contract;

use Illuminate\Database\Eloquent\Builder;
interface SearchValueObjectContract
{
    public function apply(Builder $builder): Builder;
}
