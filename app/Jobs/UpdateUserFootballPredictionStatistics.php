<?php

namespace App\Jobs;

use App\Domain\Football\Match\PredictionResult;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Models\UserFootballPredictionStatistics;
use DB;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateUserFootballPredictionStatistics implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $userId, private PredictionResult $result, private float|int $gainPoints) {}

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        if ($user === null) {
            throw new UserNotFoundException("User not found id: {$this->userId} ");
        }
        $updateColumn = $this->selectColumnToUpdate();
        UserFootballPredictionStatistics::query()->updateOrCreate([
            'user_id' => $user->id,
        ], [
            $updateColumn => DB::raw("COALESCE($updateColumn, 0) + 1"),
            'points' => DB::raw('COALESCE(points, 0) + '.$this->gainPoints),
        ]);
    }

    /**
     * @throws Exception
     */
    private function selectColumnToUpdate(): string
    {
        return match ($this->result) {
            PredictionResult::WIN => 'win',
            PredictionResult::HALF => 'win_half',
            PredictionResult::DRAW => 'draw',
            PredictionResult::LOSE => 'lose',
            default => throw new Exception('Prediction result not match : '.$this->result->value)
        };
    }
}
