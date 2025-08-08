<?php

namespace App\Events;

use App\Models\UserSilverCoinTransactionLog;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SilverCoinChangeHistoryEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        private UserSilverCoinTransactionLog $transactionLog
    ) {}

    public function getTransactionLog(): UserSilverCoinTransactionLog
    {
        return $this->transactionLog;
    }
}
