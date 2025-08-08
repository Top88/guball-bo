<?php

namespace App\Domain\Football\Match;

use App\Domain\Football\RateType;
use App\Infrastructure\Matches\MatchRepository;
use App\Models\GameFootballMatch;
use App\Models\GameFootballPrediction;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

readonly class MatchService
{
    public function __construct(private MatchRepository $matchRepository) {}

    public function getMatches(?string $search, int $size = 10, int $page = 1, string $pageName = 'page', string $orderBy = 'asc'): LengthAwarePaginator
    {
        return $this->matchRepository->filter($search, $size, $page, $pageName, $orderBy);
    }

    public function deleteMatch(int $id): bool
    {
        return $this->matchRepository->delete($id);
    }

    public function removeLeague(int $leagueId): bool
    {
        return $this->matchRepository->removeLeague($leagueId);
    }

    public function createMatch(
        string $matchDate,
        string $matchTime,
        ?int $leagueId,
        string $homeTeam,
        string $awayTeam,
        string $rateType,
        ?string $rate,
        ?string $favoriteTeam,
    ): GameFootballMatch {
        return $this->matchRepository->create(
            Carbon::createFromFormat('Y-m-d H:i:s', "$matchDate $matchTime"),
            $leagueId,
            $homeTeam,
            $awayTeam,
            RateType::from($rateType),
            $rate,
            $favoriteTeam,
        );
    }

    public function updateMatch(
        int $id,
        string $matchDate,
        string $matchTime,
        ?int $leagueId,
        string $homeTeam,
        string $awayTeam,
        string $rateType,
    ): bool {
        return $this->matchRepository->update(
            $id,
            Carbon::createFromFormat('Y-m-d H:i:s', "$matchDate $matchTime"),
            $leagueId,
            $homeTeam,
            $awayTeam,
            RateType::from($rateType),
        );
    }

    /**
     * อัปเดตผลการทายลง game_football_prediction หลังจากสรุปผล
     */
    public function updatePredictionResult(GameFootballMatch $match): void
    {
        if (is_null($match->team_match_result)) {
            return;
        }

        DB::transaction(function () use ($match) {
            $predictions = GameFootballPrediction::where('match_id', $match->id)->get();

            foreach ($predictions as $prediction) {
                if ($match->team_match_result === 'draw') {
                    $prediction->result = 'draw';
                    $prediction->gain_type = 'gold_coins';
                    $prediction->gain_amount = 0;
                } elseif ($prediction->selected_team_id && $prediction->selected_team_id === $match->team_win_id) {
                    $prediction->result = 'win';
                    $prediction->gain_type = 'gold_coins';
                    $prediction->gain_amount = config('settings.gold_coin_win', 100);
                } else {
                    $prediction->result = 'lose';
                    $prediction->gain_type = 'gold_coins';
                    $prediction->gain_amount = 0;
                }

                $prediction->updated_by = auth()->id() ?? 'system'; // ป้องกันกรณี auth ไม่มี
                $prediction->save();
            }
        });
    }

}
