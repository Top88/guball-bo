<?php

namespace App\Console\Commands;

use App\Domain\Football\Match\PredictionResult;
use App\Models\GameFootballPrediction;
use App\Models\User;
use Illuminate\Console\Command;

class SyncUserStatisticCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-user-statistic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        User::query()->with(['gameFootBallPrediction'])->chunk(20, function ($users) {
           $users->each(function (User $user) {
              $win = $user->gameFootBallPrediction->where('result', PredictionResult::WIN->value)->count();
              $half = $user->gameFootBallPrediction->where('result', PredictionResult::HALF->value)->count();
              $draw = $user->gameFootBallPrediction->where('result', PredictionResult::DRAW->value)->count();
              $lose = $user->gameFootBallPrediction->where('result', PredictionResult::LOSE->value)->count();
              $gain = $user->loadSum('gameFootBallPrediction', 'gain_amount');
              $user->footballPredictionStatistic()->updateOrCreate([
                  'user_id' => $user->id,
              ], [
                  'win' => $win,
                  'win_half' => $half,
                  'draw' => $draw,
                  'lose' => $lose,
                  'points' => $gain->game_foot_ball_prediction_sum_gain_amount ?? 0
              ]);
           });
        });
    }
}
