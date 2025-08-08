<?php

namespace App\Jobs;

use App\Domain\Coin\GoldCoinHistoryAction;
use App\Domain\Credit\ExchangeCreditStatus;
use App\Domain\Prediction\CurrencyType;
use App\Domain\Transactions\UserGoldCoinTransactionLog;
use App\Events\GoldCoinsChangeHistoryEvent;
use App\Models\ExchangeCredit;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ExchangeCreditJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $userId, private string $updatedBy)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        if (!$user) {
            return;
        }
        $current = $user->coins_gold;
        $cost = config('settings.exchange_credit_cost', '2000');
        DB::beginTransaction();
        $user->decrement('coins_gold', $cost);
        $user->refresh();
        ExchangeCredit::query()->create([
            'user_id' => $user->id,
            'cost_type' => CurrencyType::GOLD_COIN,
            'cost_amount' => $cost,
            'credit_amount' => config('settings.exchange_credit_gain_amount', '0'),
            'exchange_status' => ExchangeCreditStatus::PENDING,
            'updated_by' => $this->updatedBy,
        ]);
        DB::commit();
        $preLog = new UserGoldCoinTransactionLog(
            $user->id,
            GoldCoinHistoryAction::EXCHANGE_TO_CREDIT,
            $current,
            $cost,
            $user->coins_gold,
            $this->updatedBy,
        );
        GoldCoinsChangeHistoryEvent::dispatch($preLog);
    }
}
