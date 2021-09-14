<?php

namespace App\ValueObjects;

use App\ValueObjects\Contract\SearchValueObjectContract;
use Illuminate\Database\Eloquent\Builder;

class AccountSearchValueObject implements SearchValueObjectContract
{

    private ?int $parentId;
    private ?string $name;

    /**
     * @param int|null $parentId
     * @param string|null $name
     */
    public function __construct(?int $parentId, ?string $name)
    {
        $this->parentId = $parentId;
        $this->name = $name;
    }

    public function apply(Builder $builder): Builder
    {
        return $builder->when(
            $this->getName(),
            fn($builder) => $builder->where(function ($subQuery) {
                return $subQuery->where('name', 'ilike', '%' . $this->getName() . '%')->orWhere('ar_name', 'ilike', '%' . $this->getName() . '%');
            })
        )->when(
            $this->getParentId(),
            fn($builder) => $builder->where('parent_id', $this->getParentId())
        );
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getParentId(): ?int
    {
        return $this->parentId;
    }


}
