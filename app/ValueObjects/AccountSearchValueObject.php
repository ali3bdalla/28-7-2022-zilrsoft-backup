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

    public function applyToQueryBuilder(Builder $builder): Builder
    {
        if ($this->getName())
            $builder->where(function ($subQuery) {
                return $subQuery->where('name', 'like', '%' . $this->getName() . '%')->orWhere('ar_name', 'like', '%' . $this->getName() . '%');
            });
        if ($this->getParentId())
            $builder->where('parent_id', $this->getParentId());

        return $builder;
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
