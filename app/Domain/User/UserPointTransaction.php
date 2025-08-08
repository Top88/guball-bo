<?php

namespace App\Domain\User;

use App\Domain\Coin\GoldCoinHistoryAction;
use App\Domain\Transactions\GameType;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class UserPointTransaction implements Arrayable
{
    public function __construct(
        private string $userId,
        private GoldCoinHistoryAction $pointHistoryAction,
        private float $current,
        private float $change,
        private float $balance,
        private string $updatedBy,
        private ?int $id = null,
        private ?GameType $gameType = null,
        private ?int $gameId = null,
        private ?string $note = null,
        private ?Carbon $createdAt = null,
        private ?Carbon $updatedAt = null,
    ) {}

    public function toArray(): array
    {
        return array_filter([
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'action' => $this->getPointHistoryAction()->value,
            'game_type' => $this->getGameType()?->value,
            'game_id' => $this->getGameId(),
            'current' => $this->getCurrent(),
            'change' => $this->getChange(),
            'balance' => $this->getBalance(),
            'note' => $this->getNote(),
            'created_at' => $this->getCreatedAt()?->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()?->toDateTimeString(),
        ]);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPointHistoryAction(): GoldCoinHistoryAction
    {
        return $this->pointHistoryAction;
    }

    public function getCurrent(): float
    {
        return $this->current;
    }

    public function getChange(): float
    {
        return $this->change;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getUpdatedBy(): string
    {
        return $this->updatedBy;
    }

    public function getGameType(): ?GameType
    {
        return $this->gameType;
    }

    public function getGameId(): ?int
    {
        return $this->gameId;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }
}
