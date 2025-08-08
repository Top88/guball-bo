<?php

namespace App\Domain\Transactions;

use App\Domain\Coin\SilverCoinHistroryAction;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;

class UserSilverCoinTransactionLog implements Arrayable
{
    /**
     * Summary of __construct
     *
     * @param  SilverCoinHistroryAction  $pointHistoryAction
     * @param  mixed  $gameType
     * @param  mixed  $gameId
     * @param  mixed  $note
     * @param  mixed  $createdAt
     * @param  mixed  $updatedAt
     */
    public function __construct(
        private string $userId,
        private SilverCoinHistroryAction $action,
        private float $current,
        private float $change,
        private float $balance,
        private string $updatedBy,
        private ?GameType $gameType = null,
        private ?int $gameId = null,
        private ?string $note = null,
        private ?Carbon $createdAt = null,
        private ?Carbon $updatedAt = null,
        private ?int $id = null,
    ) {}

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getAction(): SilverCoinHistroryAction
    {
        return $this->action;
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

    public function getCreatedAt(): ?Carbon
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?Carbon
    {
        return $this->updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return array_filter([
            'id' => $this->getId(),
            'user_id' => $this->getUserId(),
            'action' => $this->getAction()->value,
            'game_type' => $this->getGameType()?->value,
            'game_id' => $this->getGameId(),
            'current' => $this->getCurrent(),
            'change' => $this->getChange(),
            'balance' => $this->getBalance(),
            'note' => $this->getNote(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt()?->toDateTimeString(),
            'updated_at' => $this->getUpdatedAt()?->toDateTimeString(),
        ], function ($value) {
            return $value !== null;
        });
    }
}
