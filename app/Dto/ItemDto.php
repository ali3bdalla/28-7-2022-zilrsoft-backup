<?php

namespace App\Dto;

use App\Models\Category;
use App\Models\Manager;
use Illuminate\Support\Str;

class ItemDto
{
    private Manager $manager;
    private Category $category;
    private string $name;
    private string $arName;
    private string $barcode;
    private float $price;
    private float $taxPercent;
    private float $onlinePrice;
    private bool $isNeedSerial;
    private bool $itKit;
    private bool $isFixedPrice;
    private bool $isService;
    private bool $isExpense;
    private string $description;
    private string $arDescription;
    private bool $isAvailableOnline;
    private float $shippingDiscount;
    private float $shippingWeight;

    /**
     * @param Manager $manager
     * @param Category $category
     * @param string $name
     * @param string $arName
     * @param string $barcode
     * @param float $price
     * @param float $taxPercent
     * @param float $onlinePrice
     * @param bool $isService
     * @param bool $isExpense
     * @param bool $isNeedSerial
     * @param bool $itKit
     * @param bool $isFixedPrice
     * @param bool $isAvailableOnline
     * @param float $shippingDiscount
     * @param float $shippingWeight
     * @param string $description
     * @param string $arDescription
     */
    public function __construct(
        Manager  $manager,
        Category $category,
        string   $name,
        string   $arName,
        string   $barcode,
        float    $price,
        float    $taxPercent,
        float    $onlinePrice,
        bool     $isService = false,
        bool     $isExpense = false,
        bool     $isNeedSerial = false,
        bool     $itKit = false,
        bool     $isFixedPrice = false,
        bool     $isAvailableOnline = true,
        float    $shippingDiscount = 0,
        float    $shippingWeight = 0,
        string   $description = "",
        string   $arDescription = ""
    )
    {
        $this->manager = $manager;
        $this->category = $category;
        $this->name = $name;
        $this->arName = $arName;
        $this->barcode = $barcode;
        $this->price = $price;
        $this->taxPercent = $taxPercent;
        $this->onlinePrice = $onlinePrice;
        $this->itKit = $itKit;
        $this->isFixedPrice = $isFixedPrice;
        $this->isService = $isService;
        $this->isAvailableOnline = $isAvailableOnline;
        $this->shippingDiscount = $shippingDiscount;
        $this->shippingWeight = $shippingWeight;
        $this->isNeedSerial = $isNeedSerial;
        $this->isExpense = $isExpense;
        $this->description = $description;
        $this->arDescription = $arDescription;
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
     * @return int
     */
    public function getManagerId(): int
    {
        return $this->manager->id;
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
     * @return int
     */
    public function getOrganizationId(): int
    {
        return $this->manager->organization_id;
    }

    /**
     * @return int
     */
    public function getCategoryId(): int
    {
        return $this->category->id;
    }

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getTaxedPrice(): float
    {
        return $this->price * (1 + $this->taxPercent / 100);
    }

    /**
     * @return float
     */
    public function getVtp(): float
    {
        return $this->taxPercent;
    }

    /**
     * @return float
     */
    public function getVts(): float
    {
        return $this->taxPercent;
    }

    /**
     * @return float
     */
    public function getOnlinePrice(): float
    {
        return $this->onlinePrice;
    }

    /**
     * @return bool
     */
    public function isItKit(): bool
    {
        return $this->itKit;
    }

    /**
     * @return bool
     */
    public function isFixedPrice(): bool
    {
        return $this->isFixedPrice;
    }

    /**
     * @return bool
     */
    public function isService(): bool
    {
        return $this->isService;
    }

    /**
     * @return bool
     */
    public function isAvailableOnline(): bool
    {
        return $this->isAvailableOnline;
    }

    /**
     * @return float|int
     */
    public function getShippingDiscount()
    {
        return $this->shippingDiscount;
    }

    /**
     * @return float|int
     */
    public function getShippingWeight()
    {
        return $this->shippingWeight;
    }

    /**
     * @return bool
     */
    public function isNeedSerial(): bool
    {
        return $this->isNeedSerial;
    }

    /**
     * @return bool
     */
    public function isExpense(): bool
    {
        return $this->isExpense;
    }

}
