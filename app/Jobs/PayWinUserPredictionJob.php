<?php

namespace App\Jobs;

use App\Domain\Coin\GoldCoinHistoryAction;
use App\Domain\Football\Match\PredictionResult;
use App\Domain\Football\Match\TeamMatchResult;
use App\Domain\Football\Match\WinType;
use App\Domain\Prediction\CurrencyType;
use App\Domain\Transactions\GameType;
use App\Domain\Transactions\UserGoldCoinTransactionLog;
use App\Events\GoldCoinsChangeHistoryEvent;
use App\Exceptions\UserNotFoundException;
use App\Models\GameFootballMatchResult;
use App\Models\GameFootballPrediction;
use App\Models\User;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PayWinUserPredictionJob implements ShouldQueue
{
    use Queueable;

    /**
     * Summary of __construct
     */
    public function __construct(private GameFootballPrediction $predic, private GameFootballMatchResult $match, private string $updatedBy)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        try {
            /** @var User $user */
            $user = User::find($this->predic->user_id);
            if ($user === null) {
                throw new UserNotFoundException;
            }
            $result = $this->getResult();
            $gain = $this->gainAmount($result);
            $this->predic->update([
                'result' => $result->value,
                'gain_type' => CurrencyType::GOLD_COIN->value,
                'gain_amount' => $gain,
                'updated_by' => $this->updatedBy,
            ]);
            $current = $user->coins_gold;
            $user->increment('coins_gold', $gain);
            $user->refresh();
            DB::commit();
            GoldCoinsChangeHistoryEvent::dispatch(new UserGoldCoinTransactionLog(
                $this->predic->user_id,
                $this->getGoldCoinHistoryAction($result),
                $current,
                $gain,
                $user->coins_gold,
                $this->updatedBy,
                GameType::FOOTBALL_MATCH,
                $this->predic->match_id,
            ));
            UpdateUserFootballPredictionStatistics::dispatch($this->predic->user_id, $result, $gain);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getGoldCoinHistoryAction(PredictionResult $result): GoldCoinHistoryAction
    {
        return match ($result) {
            PredictionResult::WIN,
            PredictionResult::HALF => GoldCoinHistoryAction::WIN_FOOTBALL_PREDICTION,
            PredictionResult::DRAW => GoldCoinHistoryAction::DRAW_FOOTBALL_PREDICTION,
            PredictionResult::LOSE => GoldCoinHistoryAction::LOSE_FOOTBALL_PREDICTION,
            default => throw new \Exception('Result not found'.$result->value),
        };
    }

    public function getResult(): PredictionResult
    {
        $teamResult = TeamMatchResult::tryFrom($this->match->team_match_result);
        if ($teamResult === TeamMatchResult::DRAW) {
            return PredictionResult::DRAW;
        } elseif ($this->predic->selected_team_id === $this->match->team_win_id && $this->match->win_type === WinType::FULL->value) {
            return PredictionResult::WIN;
        } elseif ($this->predic->selected_team_id === $this->match->team_win_id && $this->match->win_type === WinType::HALF->value) {
            return PredictionResult::HALF;
        } elseif ($this->predic->selected_team_id !== $this->match->team_win_id) {
            return PredictionResult::LOSE;
        }
        throw new \Exception('Result not found'.json_encode($this->match->team_match_result));
    }

    public function gainAmount(PredictionResult $teamMatchResult): float|int
    {
        return match ($teamMatchResult) {
            PredictionResult::WIN => (float) config('settings.win_get_coin'),
            PredictionResult::HALF => (float) config('settings.win_half_get_coin'),
            PredictionResult::DRAW => (float) config('settings.draw_get_coin'),
            PredictionResult::LOSE => (float) config('settings.lose_get_coin'),
            default => 0,
        };
    }
}
