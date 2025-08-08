<?php

namespace App\Infrastructure\Points;

use App\Domain\Coin\GoldCoinHistoryAction;
use App\Domain\Transactions\GameType;
use App\Models\UserGoldCoinTransactionLog;

class PointHistoryRepository
{
    public function __construct(private UserGoldCoinTransactionLog $pointHistory) {}

    /**
     * Summary of create
     */
    public function create(
        string $userId,
        GoldCoinHistoryAction $action,
        float $current,
        float $change,
        float $balance,
        string $updatedBy,
        ?GameType $gameType = null,
        ?int $gameId = null,
        ?string $note = null,
    ): void {
        $this->pointHistory->create([
            'user_id' => $userId,
            'action' => $action->value,
            'game_type' => $gameType?->value,
            'game_id' => $gameId,
            'current' => $current,
            'change' => $change,
            'balance' => $balance,
            'note' => $note,
            'updated_by' => $updatedBy,
        ]);
    }
}
