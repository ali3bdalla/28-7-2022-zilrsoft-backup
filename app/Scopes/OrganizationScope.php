<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class OrganizationScope implements Scope
{
    private $organizationId;

    public function __construct($organizationId = 1)
    {
        $this->organizationId = $organizationId;
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
        if (Schema::hasColumn($builder->getModel()->getTable(), 'organization_id')) {
            $builder->where($builder->qualifyColumn('organization_id'), (int)$this->organizationId);
        }
    }
}
