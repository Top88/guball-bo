<?php

namespace App\Infrastructure\User;

use App\Infrastructure\User\Enums\UserStatus;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Crypt;

class UserRepository
{
    /**
     * @return void
     */
    public function __construct(private User $user) {}

    public function getAllUsers(int $size, int $page, ?string $search, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->user->search($search)->paginate($size, ['*'], $pageName, $page);
    }

    public function getUserByPhone(string $phone): ?User
    {
        return $this->user->where('phone', $phone)->first();
    }

    public function getUserbyId(string $id): ?User
    {
        return $this->user->find($id);
    }

    public function register(string $nickName, string $fullName, string $phone, string $password, UserStatus $status, float $coins_silver = 0, float $coins_gold = 0): User
    {
        return $this->user->create([
            'nick_name' => $nickName,
            'full_name' => $fullName,
            'phone' => $phone,
            'password' => $password,
            'status' => $status->value,
            'coins_silver' => $coins_silver,
            'coins_gold' => $coins_gold,
        ]);
    }

    public function changeUserStatus(string $userId, UserStatus $status): bool
    {
        return $this->user->where('id', $userId)->update(['status' => $status->value]);
    }

    public function createUser(string $phone, string $password, string $fullName, string $nickName, float $coinsSilver = 0, float $coinsGold = 0): User
    {
        return $this->user->create([
            'phone' => $phone,
            'password' => Crypt::encryptString($password),
            'full_name' => $fullName,
            'nick_name' => $nickName,
            'status' => UserStatus::ACTIVE->value,
            'coins_silver' => $coinsSilver,
            'coins_gold' => $coinsGold,
        ]);
    }

    public function updateUser(string $userId, string $phone, ?string $password, string $fullName, string $nickName, float $coinsSilver, float $coinsGold): bool
    {
        return $this->user->where('id', $userId)->update(array_filter([
            'phone' => $phone,
            'password' => $password ? Crypt::encryptString($password) : null,
            'full_name' => $fullName,
            'nick_name' => $nickName,
            'coins_silver' => $coinsSilver,
            'coins_gold' => $coinsGold,
        ]));
    }

    public function deleteUser(string $userId): bool
    {
        return $this->user->where('id', $userId)->delete();
    }
}
