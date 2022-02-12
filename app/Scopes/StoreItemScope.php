<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Route;

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

        if (Route::current() && in_array("ecommerceMiddleware", Route::current()->gatherMiddleware())) {
            $builder
                ->where([
                    [$builder->qualifyColumn('is_available_online'), true],
                    [$builder->qualifyColumn('available_qty'), '>', 0],
                    [$builder->qualifyColumn('is_kit'), false],
                ])
                ->whereHas('category')
                ->whereHas('attachments', function (Builder $builder) {

                })
                ->orderBy($builder->qualifyColumn('available_qty'), 'desc');
        }
    }
}
