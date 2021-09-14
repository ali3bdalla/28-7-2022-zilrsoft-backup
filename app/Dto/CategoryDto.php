<?php

namespace App\Dto;

use App\Models\Category;
use App\Models\Manager;
use App\ValueObjects\PhotoValueObject;
use Illuminate\Support\Str;

class CategoryDto
{
    private Manager $manager;
    private string $name;
    private string $arName;
    private string $description;
    private string $arDescription;
    private bool $isAvailableOnline;
    private ?Category $parent;
    private ?PhotoValueObject $image;
    private ?PhotoValueObject $cover;

    /**
     * @param Manager $manager
     * @param string $name
     * @param string $arName
     * @param string $description
     * @param string $arDescription
     * @param bool $isAvailableOnline
     * @param Category|null $parent
     * @param PhotoValueObject|null $image
     * @param PhotoValueObject|null $cover
     */
    public function __construct(Manager $manager, string $name, string $arName, string $description, string $arDescription, bool $isAvailableOnline, ?Category $parent = null, ?PhotoValueObject $image = null, ?PhotoValueObject $cover = null)
    {
        $this->manager = $manager;
        $this->name = $name;
        $this->arName = $arName;
        $this->description = $description;
        $this->arDescription = $arDescription;
        $this->isAvailableOnline = $isAvailableOnline;
        $this->parent = $parent;
        $this->image = $image;
        $this->cover = $cover;
    }

    /**
     * @return int
     */
    public function getOrganizationId(): int
    {
        return $this->manager->organization_id;
    }

    /**
     * @return int
     */
    public function getManagerId(): int
    {
        return $this->manager->id;
    }

    /**
     * @return string
     */
    public function getArSlug(): string
    {
        return Str::slug($this->getArName());
    }

    /**
     * @return string
     */
    public function getArName(): string
    {
        return $this->arName;
    }

    /**
     * @return string
     */
    public function getEnSlug(): string
    {
        return Str::slug($this->getName());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getArDescription(): string
    {
        return $this->arDescription;
    }

    /**
     * @return bool
     */
    public function isAvailableOnline(): bool
    {
        return $this->isAvailableOnline;
    }

    /**
     * @return int
     */
    public function getParentId(): int
    {
        return $this->parent ? $this->parent->id : 0;
    }

    /**
     * @param Category|null $parent
     */
    public function setParent(?Category $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getImagePath(): string
    {
        return $this->image ? $this->image->getStoragePath() : "";
    }

    /**
     * @param PhotoValueObject|null $image
     */
    public function setImage(?PhotoValueObject $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getCoverPath(): string
    {
        return $this->cover ? $this->cover->getStoragePath() : "";
    }

    /**
     * @param PhotoValueObject|null $cover
     */
    public function setCover(?PhotoValueObject $cover): void
    {
        $this->cover = $cover;
    }

}
