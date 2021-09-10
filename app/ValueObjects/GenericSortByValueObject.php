<?php

namespace App\ValueObjects;

use App\ValueObjects\Contract\SortingValueObjectContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class GenericSortByValueObject implements SortingValueObjectContract
{

    private ?string $sortBy;
    private ?string $sortDirection;
    private Model $model;

    /**
     * @param string|null $sortBy
     * @param string|null $sortDirection
     * @param Model $model
     */
    public function __construct(Model $model, ?string $sortBy, ?string $sortDirection)
    {
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
        $this->model = $model;
    }

    public function sort(Builder $builder): Builder
    {
        if ($this->isValid())
            $builder->orderBy($this->getSortBy(), $this->getSortDirection());
        return $builder;
    }

    public function isValid(): bool
    {
        return Schema::hasColumn($this->model->getTable(), $this->getSortBy()) && in_array($this->getSortDirection(), ['desc', 'asc']);
    }

    /**
     * @return string|null
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy ?: "id";
    }

    /**
     * @param string|null $sortBy
     */
    public function setSortBy(?string $sortBy): void
    {
        $this->sortBy = $sortBy;
    }

    /**
     * @return string|null
     */
    public function getSortDirection(): ?string
    {
        return $this->sortDirection ?: 'asc';
    }

    /**
     * @param string|null $sortDirection
     */
    public function setSortDirection(?string $sortDirection): void
    {
        $this->sortDirection = $sortDirection;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }
}
