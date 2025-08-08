<?php

namespace App\Domain\Football\Team;

use App\Infrastructure\Teams\TeamRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class TeamService
{
    public function __construct(
        private TeamRepository $teamRepository,
    ) {}

    /**
     * Summary of getLeagues
     *
     * @param  mixed  $search
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getTeams(?string $search, int $size = 10, int $page = 1, string $pageName = 'page'): Collection|LengthAwarePaginator
    {
        if ($size === 0) {
            return $this->teamRepository->getAll();
        }

        return $this->teamRepository->filter($search, $size, $page, $pageName);
    }

    public function createTeam(string $teamName, ?int $leagueId)
    {
        return $this->teamRepository->create($teamName, $leagueId);
    }

    public function updateTeam(int $teamId, string $teamName, ?int $leagueId)
    {
        return $this->teamRepository->update($teamId, $teamName, $leagueId);
    }

    public function deleteTeam(int $teamId)
    {
        return $this->teamRepository->delete($teamId);
    }
}
