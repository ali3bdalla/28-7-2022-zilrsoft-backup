<?php

namespace App\Dto;

use App\Models\Manager;
use Propaganistas\LaravelPhone\PhoneNumber;

class UserDto
{
    private string $firstName;
    private string $lastName;
    private string $password;
    private PhoneNumber $phoneNumber;
    private string $email;
    private Manager $manager;
    private bool $isClient;
    private bool $isVendor;
    private string $userType;
    private string $userTitle;
    private ?int $verificationCode;
    private string $userSlug;

    /**
     * @param Manager $manager
     * @param PhoneNumber $phoneNumber
     * @param string $firstName
     * @param string $lastName
     * @param string $password
     * @param bool $isClient
     * @param bool $isVendor
     * @param string $email
     * @param string $userType
     * @param string $userSlug
     * @param string $userTitle
     * @param int|null $verificationCode
     */
    public function __construct(
        Manager     $manager,
        PhoneNumber $phoneNumber,
        string      $firstName,
        string      $lastName,
        string      $password,
        bool        $isClient = true,
        bool        $isVendor = true,
        string      $email = "",
        string      $userType = 'individual',
        string      $userSlug = 'online_user',
        string      $userTitle = "mr",
        ?int        $verificationCode = null
    )
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
        $this->email = $email;
        $this->manager = $manager;
        $this->isClient = $isClient;
        $this->isVendor = $isVendor;
        $this->userType = $userType;
        $this->userTitle = $userTitle;
        $this->verificationCode = $verificationCode;
        $this->userSlug = $userSlug;
    }

    /**
     * @return string
     */
    public function getUserSlug(): string
    {
        return $this->userSlug;
    }

    /**
     * @param string $userSlug
     */
    public function setUserSlug(string $userSlug): void
    {
        $this->userSlug = $userSlug;
    }

    /**
     * @return string
     */
    public function getUserType(): string
    {
        return $this->userType;
    }

    /**
     * @param string $userType
     */
    public function setUserType(string $userType): void
    {
        $this->userType = $userType;
    }

    /**
     * @return string
     */
    public function getUserTitle(): string
    {
        return $this->userTitle;
    }

    /**
     * @param string $userTitle
     */
    public function setUserTitle(string $userTitle): void
    {
        $this->userTitle = $userTitle;
    }

    /**
     * @return int|null
     */
    public function getVerificationCode(): ?int
    {
        return $this->verificationCode;
    }

    /**
     * @param int|null $verificationCode
     */
    public function setVerificationCode(?int $verificationCode): void
    {
        $this->verificationCode = $verificationCode;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return (string)$this->phoneNumber;
    }

    /**
     * @param PhoneNumber $phoneNumber
     */
    public function setPhoneNumber(PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->phoneNumber->getCountry();
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return Manager
     */
    public function getManager(): Manager
    {
        return $this->manager;
    }

    /**
     * @param Manager $manager
     */
    public function setManager(Manager $manager): void
    {
        $this->manager = $manager;
    }

    /**
     * @return bool
     */
    public function isClient(): bool
    {
        return $this->isClient;
    }

    /**
     * @param bool $isClient
     */
    public function setIsClient(bool $isClient): void
    {
        $this->isClient = $isClient;
    }

    /**
     * @return bool
     */
    public function isVendor(): bool
    {
        return $this->isVendor;
    }

    /**
     * @param bool $isVendor
     */
    public function setIsVendor(bool $isVendor): void
    {
        $this->isVendor = $isVendor;
    }
}
