<?php

namespace App\Jobs;

use App\Domain\Coin\GoldCoinHistoryAction;
use App\Domain\Coin\SilverCoinHistroryAction;
use App\Exceptions\NotEnoughPointForExchangeCoin;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Models\UserGoldCoinTransactionLog;
use App\Models\UserSilverCoinTransactionLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ExchangePointJob implements ShouldQueue, ShouldQueueAfterCommit
{
    use InteractsWithQueue, Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $userId,
        private float $exchangeAmount,
        private string $updatedBy,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        /**
         * @var User $user
         */
        $user = User::find($this->userId);
        if ($user === null) {
            throw new UserNotFoundException;
        }
        $currentPoint = $user->points;
        $cost = $this->cost();
        if ($user->points < $cost) {
            throw new NotEnoughPointForExchangeCoin($cost);
        }
        try {
            $user->decrement('points', $cost);
            $user->refresh();
            UserGoldCoinTransactionLog::create([
                'user_id' => $this->userId,
                'action' => GoldCoinHistoryAction::EXCHANGE_TO_COIN->value,
                'current' => $currentPoint,
                'change' => (-1 * $cost),
                'balance' => $user->points,
                'updated_by' => $this->updatedBy,
            ]);

            $currentCoin = $user->coins;
            $user->increment('coins', $this->exchangeAmount);
            $user->refresh();
            UserSilverCoinTransactionLog::create([
                'user_id' => $this->userId,
                'action' => SilverCoinHistroryAction::EXCHANGE_FROM_POINT->value,
                'current' => $currentCoin,
                'change' => $this->exchangeAmount,
                'balance' => $user->coins,
                'updated_by' => $user->id,
            ]);
            Auth::setUser($user);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->fail($e);
            throw new UnprocessableEntityHttpException('Can not exchange point to coin');
        }
    }

    private function cost()
    {
        if (! config('settings.cost_for_point_to_coin')) {
            throw new \Exception('No cost setting for exchange');
        }

        return $this->exchangeAmount * config('settings.cost_for_point_to_coin', 0);
    }
}
