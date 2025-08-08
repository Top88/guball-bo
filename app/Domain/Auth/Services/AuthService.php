<?php

namespace App\Domain\Auth\Services;

use App\Domain\User\UserEntities;
use App\Infrastructure\User\Enums\UserStatus;
use App\Infrastructure\User\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class AuthService
{
    /**
     * @return void
     */
    public function __construct(private UserRepository $userRepository) {}

    public function login(string $phone, string $password, bool $rememberMe = false): bool
    {
        $user = $this->userRepository->getUserByPhone($phone);
        if ($user !== null && Crypt::decryptString($user->password) === $password) {
            Auth::loginUsingId($user->id, $rememberMe);

            return true;
        }

        return false;
    }

    public function register(string $nickName, string $fullName, string $phone, string $password, float $coins = 0, float $points = 0): UserEntities
    {
        $user = $this->userRepository->register(
            $nickName,
            $fullName,
            $phone,
            Crypt::encryptString($password),
            UserStatus::ACTIVE
        );
        if (! $user) {
            throw new Exception('Cannot create user');
        }
        $user->assignRole('user');

        return new UserEntities(
            $user->id,
            $user->nick_name,
            $user->full_name,
            $user->phone,
            UserStatus::from($user->status),
            $user->coins_silver,
            $user->coins_gold,
            $user->email,
            $user->created_at,
            $user->updated_at
        );
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
