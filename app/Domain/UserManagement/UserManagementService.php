<?php

namespace App\Domain\UserManagement;

use App\Domain\Coin\GoldCoinHistoryAction;
use App\Domain\Coin\SilverCoinHistroryAction;
use App\Domain\Transactions\UserGoldCoinTransactionLog;
use App\Events\GoldCoinsChangeHistoryEvent;
use App\Exceptions\UserNotFoundException;
use App\Infrastructure\Coins\SilverCoinHistoryFactory;
use App\Infrastructure\User\Enums\UserStatus;
use App\Infrastructure\User\UserRepository;
use App\Jobs\CreateUserSilverCoinLogJob;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserManagementService
{
    public function __construct(private UserRepository $userRepository) {}

    public function getAllUsers(int $size, int $page, ?string $search, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->userRepository->getAllUsers($size, $page, $search, $pageName);
    }

    public function changeUserStatus(string $userId, string $status): bool
    {
        return $this->userRepository->changeUserStatus($userId, UserStatus::from($status));
    }

    public function createUser(string $phone, string $password, string $fullName, string $nickName, float $coinsSilver, float $coinsGold, string $updatedBy): User
    {
        $result = $this->userRepository->createUser($phone, $password, $fullName, $nickName, $coinsSilver, $coinsGold);
        if (! $result) {
            throw new \Exception('Cannot create user');
        }
        $preLog = SilverCoinHistoryFactory::willCreate(
            $result->id,
            SilverCoinHistroryAction::CREATE_USER,
            0,
            $coinsSilver,
            $coinsSilver,
            $updatedBy,
        );
        CreateUserSilverCoinLogJob::dispatch($preLog);

        return $result;
    }

    public function updateUser(string $userId, string $phone, string $password, string $fullName, string $nickName, float $coinsSilver, float $coinsGold, string $updatedBy): bool
    {
        $user = User::find($userId);
        if (! $user) {
            throw new UserNotFoundException;
        }
        $result = $this->userRepository->updateUser($userId, $phone, $password, $fullName, $nickName, $coinsSilver, $coinsGold);

        if ($result && $user->coins_silver !== $coinsSilver) {
            $preLog = SilverCoinHistoryFactory::willCreate(
                $user->id,
                SilverCoinHistroryAction::UPDATE_USER,
                $user->coins_silver,
                $coinsSilver - (float) $user->coins_silver,
                $coinsSilver,
                $updatedBy,
            );
            CreateUserSilverCoinLogJob::dispatch($preLog);
        }

        if ($result && $user->coins_gold !== $coinsGold) {
            $preLog = new UserGoldCoinTransactionLog(
                $user->id,
                GoldCoinHistoryAction::UPDATE_USER,
                $user->coins_gold,
                $coinsGold - (float) $user->coins_gold,
                $coinsGold,
                $updatedBy,
            );
            GoldCoinsChangeHistoryEvent::dispatch($preLog);
        }

        return $result;
    }

    public function getUserByPhone(string $phone): ?User
    {
        return $this->userRepository->getUserByPhone($phone);
    }

    public function getUserbyId(string $userId): ?User
    {
        return $this->userRepository->getUserById($userId);
    }

    public function deleteUser(string $userId): bool
    {
        return $this->userRepository->deleteUser($userId);
    }
}
