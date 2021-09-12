<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\UserRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserRepository extends BaseRepository implements UserRepositoryContract
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }


    public function createUser(string $phoneNumber, string $password, string $firstName, string $lastName, string $countryCode): User
    {
        $attributes = [
            'country_code' => $countryCode,
            'phone_number' => $phoneNumber,
            'password' => Hash::make($password),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'name' => $this->createName($firstName, $lastName),
            'ar_name' => $this->createName($firstName, $lastName),
            'creator_id' => 1,
            'organization_id' => 1,
            'user_slug' => 'online_user',
            'is_client' => true,
            'user_type' => 'individual',
            'user_title' => 'mr',
            'verification_code' => $this->createVerificationCode()
        ];
        $user = $this->getUnVerifiedOnlineUser($phoneNumber);
        if ($user) $user->update($attributes);
        else $user = User::create($attributes);
        return $user;
    }


    private function createName(string $firstName, string $lastName): string
    {
        return $firstName . ' ' . $lastName;
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
        if (Auth::guard('client')->once([
                'phone_number' => $phoneNumber,
                'password' => $password,
                'user_slug' => 'online_user',
            ]) && $user) {
            Auth::guard('client')->login($user, true);
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
}
