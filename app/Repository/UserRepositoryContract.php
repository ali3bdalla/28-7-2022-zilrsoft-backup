<?php

namespace App\Repository;

use App\Models\User;

interface UserRepositoryContract extends BaseRepositoryContract
{
    public function createUser(string $phoneNumber, string $password, string $firstName, string $lastName, string $countryCode): User;

    public function getUnVerifiedOnlineUser(string $phoneNumber): ?User;

    public function getVerifiedOnlineUser(string $phoneNumber): ?User;

    public function ensureIsValidVerificationCode(User $user, int $verificationCode);

    public function verifyUser(int $userId, int $verificationCode): ?User;

    public function isLoggedAsOnlineUser(string $phoneNumber, string $password): bool;

    public function changeUserPassword(User $user, string $password);

    public function createForgetPasswordVerificationCode(string $phoneNumber): ?User;
}
