<?php

namespace App\Repository;

use App\Dto\UserDto;
use App\Models\User;

interface UserRepositoryContract extends BaseRepositoryContract
{
    public function createUser(UserDto $userDto): User;

    public function getUnVerifiedOnlineUser(string $phoneNumber): ?User;

    public function getVerifiedOnlineUser(string $phoneNumber): ?User;

    public function ensureIsValidVerificationCode(User $user, int $verificationCode);

    public function verifyUser(int $userId, int $verificationCode): ?User;

    public function isLoggedAsOnlineUser(string $phoneNumber, string $password): bool;

    public function changeUserPassword(User $user, string $password);

    public function createForgetPasswordVerificationCode(string $phoneNumber): ?User;

    public function updateCustomerBalanceAmount(User $user,float $amount,bool $addBalance = true);

    public function updateVendorBalanceAmount(User $user,float $amount,bool $addBalance = true);
}
