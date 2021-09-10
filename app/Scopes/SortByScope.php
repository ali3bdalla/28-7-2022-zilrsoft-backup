<?php

namespace App\Scopes;

use App\ValueObjects\Contract\SortingValueObjectContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SortByScope implements Scope
{
    private SortingValueObjectContract $sortingValueObjectContract;

    public function __construct(SortingValueObjectContract $sortingValueObjectContract)
    {
        $this->sortingValueObjectContract = $sortingValueObjectContract;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder = $this->sortingValueObjectContract->sort($builder);
    }
}
