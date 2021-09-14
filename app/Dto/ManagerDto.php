<?php

namespace App\Dto;

use App\Models\Organization;

class ManagerDto
{
    private Organization $organization;
    private string $name;
    private string $email;
    private string $password;
    private int $departmentId;
    private int $brandId;

    /**
     * @param Organization $organization
     * @param string $name
     * @param string $email
     * @param string $password
     * @param int $departmentId
     * @param int $brandId
     */
    public function __construct(Organization $organization, string $name, string $email, string $password, int $departmentId = 1, int $brandId = 1)
    {
        $this->organization = $organization;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->departmentId = $departmentId;
        $this->brandId = $brandId;
    }

    /**
     * @return Organization
     */
    public function getOrganization(): Organization
    {
        return $this->organization;
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
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }


    /**
     * @return int
     */
    public function getDepartmentId(): int
    {
        return $this->departmentId;
    }
    /**
     * @return int
     */
    public function getOrganizationId(): int
    {
        return $this->organization->id;
    }

    /**
     * @return int
     */
    public function getBrandId(): int
    {
        return $this->brandId;
    }


}
