<?php

namespace App\Infrastructure\Coins;

use App\Domain\Coin\SilverCoinHistroryAction;
use App\Domain\Transactions\GameType;
use App\Domain\Transactions\UserSilverCoinTransactionLog;

class SilverCoinHistoryFactory
{
    public static function willCreate(
        string $userId,
        SilverCoinHistroryAction $action,
        float $current,
        float $change,
        float $balance,
        string $updatedBy,
        ?GameType $gameType = null,
        ?int $gameId = null,
        ?string $note = null,
    ): UserSilverCoinTransactionLog {
        return new UserSilverCoinTransactionLog(
            $userId,
            $action,
            $current,
            $change,
            $balance,
            $updatedBy,
            $gameType,
            $gameId,
            $note
        );
    }
}
