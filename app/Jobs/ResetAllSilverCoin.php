<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ResetAllSilverCoin implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $updateBy)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::query()->where('coins_silver', '>', 0)->get();
        foreach ($users as $user) {
            ResetUserSilverCoin::dispatch($user, $this->updateBy);
        }
    }
}
