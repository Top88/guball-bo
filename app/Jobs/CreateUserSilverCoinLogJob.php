<?php

namespace App\Jobs;

use App\Domain\Transactions\UserSilverCoinTransactionLog;
use App\Models\UserSilverCoinTransactionLog as UserSilverCoinTransactionLogModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CreateUserSilverCoinLogJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private UserSilverCoinTransactionLog $data,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserSilverCoinTransactionLogModel::create($this->data->toArray());
    }
}
