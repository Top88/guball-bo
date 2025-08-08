<?php

namespace App\Infrastructure\Teams;

use App\Models\GameFootballTeam;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TeamRepository
{
    public function __construct(private GameFootballTeam $gameFootballTeam) {}

    public function getAll(): Collection
    {
        return $this->gameFootballTeam->all();
    }

    public function filter(?string $search, int $size = 10, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->gameFootballTeam->with('league')->search($search)->paginate($size, ['*'], $pageName, $page);
    }

    /**
     * Summary of create
     *
     * @param  mixed  $leagueId
     */
    public function create(string $teamName, ?int $leagueId)
    {
        return $this->gameFootballTeam->create(['name' => $teamName, 'league_id' => $leagueId]);
    }

    public function update(int $teamId, string $teamName, ?int $leagueId)
    {
        return $this->gameFootballTeam->find($teamId)->update([
            'name' => $teamName,
            'league_id' => $leagueId,
        ]);
    }

    public function delete(int $teamId)
    {
        return $this->gameFootballTeam->find($teamId)->delete();
    }
}
