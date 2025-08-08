<?php

namespace App\Infrastructure\Matches;

use App\Domain\Football\RateType;
use App\Infrastructure\Matches\Enums\MatchStatus;
use App\Models\GameFootballMatch;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

readonly class MatchRepository
{
    public function __construct(private GameFootballMatch $gameFootballMatch) {}

    /**
     * @param string|null $search
     * @param int $size
     * @param int $page
     * @param string $pageName
     * @param string $orderBy
     * @return LengthAwarePaginator
     */
    public function filter(?string $search, int $size = 10, int $page = 1, string $pageName = 'page', string $orderBy = 'asc'): LengthAwarePaginator
    {
        return $this->gameFootballMatch->search($search)->orderBy('created_at', $orderBy)->paginate($size, ['*'], $pageName, $page);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->gameFootballMatch->find($id)->delete();
    }

    /**
     * @param int $leagueId
     * @return bool
     */
    public function removeLeague(int $leagueId): bool
    {
        return $this->gameFootballMatch->where('league_id', $leagueId)->update(['league_id' => null]);
    }

    /**
     * @param Carbon $matchDate
     * @param int|null $leagueId
     * @param int $homeTeam
     * @param int $awayTeam
     * @param RateType $rateType
     * @param string|null $rate
     * @param string|null $favoriteTeam
     * @return GameFootballMatch
     */
    public function create(
        Carbon $matchDate,
        ?int $leagueId,
        int $homeTeam,
        int $awayTeam,
        RateType $rateType,
        ?string $rate,
        ?string $favoriteTeam,
    ): GameFootballMatch {
        return $this->gameFootballMatch->create([
            'league_id' => $leagueId,
            'home_team_id' => $homeTeam,
            'away_team_id' => $awayTeam,
            'rate_type' => $rateType->value,
            'rate' => $rate,
            'favorite_team' => $favoriteTeam,
            'match_date' => $matchDate->toDateTimeString(),
            'status' => MatchStatus::ACTIVE->value,
        ]);
    }

    /**
     * @param int $id
     * @param Carbon $matchDate
     * @param int|null $leagueId
     * @param int $homeTeam
     * @param int $awayTeam
     * @param RateType $rateType
     * @return bool
     */
    public function update(
        int $id,
        Carbon $matchDate,
        ?int $leagueId,
        int $homeTeam,
        int $awayTeam,
        RateType $rateType,
    ): bool {
        return $this->gameFootballMatch->where('id', $id)->update([
            'league_id' => $leagueId,
            'home_team_id' => $homeTeam,
            'away_team_id' => $awayTeam,
            'rate_type' => $rateType->value,
            'match_date' => $matchDate->toDateTimeString(),
            'status' => MatchStatus::ACTIVE->value,
        ]);
    }
}
