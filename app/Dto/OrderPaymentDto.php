<?php

namespace App\Dto;

class OrderPaymentDto
{
    private string $firstName;
    private string $lastName;
    private int $senderAccountId;
    private int $receiverAccountId;

    /**
     * @param string $firstName
     * @param string $lastName
     * @param int $senderAccountId
     * @param int $receiverAccountId
     */
    public function __construct(string $firstName, string $lastName, int $senderAccountId, int $receiverAccountId)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->senderAccountId = $senderAccountId;
        $this->receiverAccountId = $receiverAccountId;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return int
     */
    public function getSenderAccountId(): int
    {
        return $this->senderAccountId;
    }

    /**
     * @return int
     */
    public function getReceiverAccountId(): int
    {
        return $this->receiverAccountId;
    }



}
