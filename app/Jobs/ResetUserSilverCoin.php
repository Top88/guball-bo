<?php

namespace App\Jobs;

use App\Domain\Coin\SilverCoinHistroryAction;
use App\Domain\Transactions\UserSilverCoinTransactionLog;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ResetUserSilverCoin implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currentCoin = $this->user->coins_silver;
        $this->user->update([
            'coins_silver' => 0,
        ]);
        $this->user->refresh();
        $prelog = new UserSilverCoinTransactionLog(
            $this->user->id,
            SilverCoinHistroryAction::ADMIN_RESET,
            $currentCoin,
            0 - (float) $currentCoin,
            $this->user->coins_silver,
            'system'
        );
        CreateUserSilverCoinLogJob::dispatch($prelog);
    }
}
