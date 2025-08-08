<?php

namespace App\Events;

use App\Domain\Transactions\UserGoldCoinTransactionLog;
use Illuminate\Foundation\Events\Dispatchable;

class GoldCoinsChangeHistoryEvent
{
    use Dispatchable;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private UserGoldCoinTransactionLog $transactionLog
    ) {}

    public function getTransactionLog(): UserGoldCoinTransactionLog
    {
        return $this->transactionLog;
    }
}
