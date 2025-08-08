<?php

namespace App\Jobs;

use App\Exceptions\MatchNotFoundException;
use App\Models\GameFootballMatchResult;
use App\Models\GameFootballPrediction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Log;

class RewardUserWinPredictionJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $matchId, private string $updatedBy)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $match = GameFootballMatchResult::where('match_id', $this->matchId)->first();
        if (! $match) {
            throw new MatchNotFoundException('Match not found');
        }

        GameFootballPrediction::where('match_id', $this->matchId)->whereNull('result')
            ->chunk(20, function ($records) use ($match) {
                try {
                    /** @var GameFootballPrediction $value */
                    foreach ($records as $value) {
                        PayWinUserPredictionJob::dispatch($value, $match, $this->updatedBy);
                    }
                } catch (\Exception $e) {
                    Log::error($e->getMessage(), $e->getTrace());
                }
            });
    }
}
