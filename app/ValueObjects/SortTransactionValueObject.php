<?php

namespace App\ValueObjects;

use App\ValueObjects\Contract\SortingValueObjectContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class SortTransactionValueObject implements SortingValueObjectContract
{

    private ?string $sortBy;
    private ?string $sortDirection;

    /**
     * @param string|null $sortBy
     * @param string|null $sortDirection
     */
    public function __construct(?string $sortBy = "", ?string $sortDirection = "asc")
    {
        $this->sortBy = $sortBy;
        $this->sortDirection = $sortDirection;
    }

    public function sort(Builder $builder): Builder
    {
        if ($this->isValid())
            $builder->orderBy($this->getSortBy(), $this->getSortDirection());
        return $builder;
    }

    public function isValid(): bool
    {
        return Schema::hasColumn('transactions', $this->getSortBy()) && in_array($this->getSortDirection(), ['desc', 'asc']);
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
}
