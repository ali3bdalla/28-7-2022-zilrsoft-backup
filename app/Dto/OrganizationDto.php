<?php

namespace App\Dto;

use App\Base\BaseDtoContract;
use App\Models\Country;
use App\Models\Type;
use App\ValueObjects\PhotoValueObject;
use Propaganistas\LaravelPhone\PhoneNumber;

class OrganizationDto  implements BaseDtoContract
{
    private string $title;
    private string $city;
    private string $description;
    private Type $type;
    private Country $country;
    private string $crNumber;
    private string $vatNumber;
    private PhoneNumber $phoneNumber;
    private ?PhotoValueObject $logo;
    private ?PhotoValueObject $stamp;

    /**
     * @param string $title
     * @param string $city
     * @param string $description
     * @param string $crNumber
     * @param string $vatNumber
     * @param PhoneNumber $phoneNumber
     * @param Type|null $type
     * @param Country|null $country
     * @param PhotoValueObject|null $logo
     * @param PhotoValueObject|null $stamp
     */
    public function __construct(string $title, string $city, string $description, string $crNumber, string $vatNumber, PhoneNumber $phoneNumber, ?Type $type = null, ?Country $country = null, ?PhotoValueObject $logo = null, ?PhotoValueObject $stamp = null)
    {
        $this->title = $title;
        $this->city = $city;
        $this->description = $description;
        $this->type = $type ?: Type::first();
        $this->country = $country ?: Country::first();
        $this->crNumber = $crNumber;
        $this->vatNumber = $vatNumber;
        $this->phoneNumber = $phoneNumber;
        $this->logo = $logo;
        $this->stamp = $stamp;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getTypeId(): int
    {
        return $this->type->id;
    }

    /**
     * @return int
     */
    public function getCountryId(): int
    {
        return $this->country->id;
    }

    /**
     * @return string
     */
    public function getCrNumber(): string
    {
        return $this->crNumber;
    }

    /**
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vatNumber;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return (string)$this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getLogoPath(): string
    {
        return $this->logo ? $this->logo->getStoragePath() : "";
    }

    /**
     * @param PhotoValueObject|null $logo
     */
    public function setLogo(?PhotoValueObject $logo): void
    {
        $this->logo = $logo;
    }

    /**
     * @return string
     */
    public function getStampPath(): string
    {
        return $this->stamp ? $this->stamp->getStoragePath() : "";
    }

    /**
     * @param PhotoValueObject|null $stamp
     */
    public function setStamp(?PhotoValueObject $stamp): void
    {
        $this->stamp = $stamp;
    }


}
