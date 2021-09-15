<?php

namespace App\Repository\Eloquent;

use App\Dto\UserDto;
use App\Models\User;
use App\Repository\UserRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryContract
{


    public function createUser(UserDto $userDto): User
    {
        $userDto->setVerificationCode($this->createVerificationCode());
        $user = $this->getUnVerifiedOnlineUser($userDto->getPhoneNumber());
        if ($user) $user->update(
            [
                'password' => Hash::make($userDto->getPassword()),
                'first_name' => $userDto->getFirstName(),
                'last_name' => $userDto->getLastName(),
                'name' => $userDto->getName(),
                'name_ar' => $userDto->getName(),
                'user_slug' => 'online_user',
                'is_client' => true,
                'user_type' => 'individual',
                'user_title' => 'mr',
                'verification_code' => $userDto->getVerificationCode()
            ]
        );
        else $user = User::factory()->setDto($user)->create();
        return $user;
    }

    private function createVerificationCode(): int
    {
        return rand(10000, 90000);
    }

    public function getUnVerifiedOnlineUser(string $phoneNumber): ?User
    {
        return User::wherePhoneNumber($phoneNumber)->whereNull("phone_number_verified_at")->whereUserSlug('online_user')->whereIsClient(true)->first();
    }

    public function verifyUser(int $userId, int $verificationCode): ?User
    {
        $user = User::findOrFail($userId);
        $this->ensureIsValidVerificationCode($user, $verificationCode);
        $user->markPhoneNumberAsVerified();
        $this->deleteUnVerifiedPhoneNumberEntities($user->phone_number, [$user->id]);
        return $user;
    }

    public function ensureIsValidVerificationCode(User $user, int $verificationCode)
    {
        if ($user->verification_code !== $verificationCode)
            abort(403);
    }

    private function deleteUnVerifiedPhoneNumberEntities(string $phoneNumber, array $except = [])
    {
        User::wherePhoneNumber($phoneNumber)
            ->whereNotIn('id', $except)
            ->whereNull("phone_number_verified_at")
            ->whereUserSlug('online_user')
            ->whereIsClient(true)->forceDelete();
    }

    public function isLoggedAsOnlineUser(string $phoneNumber, string $password): bool
    {
        $user = $this->getVerifiedOnlineUser($phoneNumber);
        if (Auth::guard('client')->validate([
                'phone_number' => $phoneNumber,
                'password' => $password,
                'user_slug' => 'online_user',
            ]) && $user) {
            Auth::guard('client')->loginUsingId($user->id, true);
            return true;
        }
        return false;
    }

    public function getVerifiedOnlineUser(string $phoneNumber): ?User
    {
        return User::wherePhoneNumber($phoneNumber)->whereNotNull("phone_number_verified_at")->whereUserSlug('online_user')->whereIsClient(true)->first();
    }

    public function createForgetPasswordVerificationCode(string $phoneNumber): ?User
    {
        $user = $this->getVerifiedOnlineUser($phoneNumber);
        if ($user) {
            $user->update(['verification_code' => $this->createVerificationCode()]);
        }

        return $user;
    }

    public function changeUserPassword(User $user, string $password)
    {
        $user->update(
            [
                'password' => Hash::make($password),
                'verification_code' => null
            ]
        );

    }

    private function createName(string $firstName, string $lastName): string
    {
        return $firstName . ' ' . $lastName;
    }
}
