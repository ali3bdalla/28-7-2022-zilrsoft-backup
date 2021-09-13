<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class StoreItemScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (strpos(url()->current(), 'web')) {
            $builder
                ->where([
                    [$builder->qualifyColumn('is_available_online'), true],
                    [$builder->qualifyColumn('available_qty'), '>', 0],
                    [$builder->qualifyColumn('is_kit'), false],
                ])
                ->with('category', 'attachments')
                ->whereHas('category')
                ->whereHas('attachments')
                ->orderBy($builder->qualifyColumn('available_qty'), 'desc');
        }
    }
}
