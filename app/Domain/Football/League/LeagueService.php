<?php

namespace App\Domain\Football\League;

use App\Domain\Football\Match\MatchService;
use App\Infrastructure\Leagues\LeagueRepository;
use App\Models\GameFootballLeague;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LeagueService
{
    public function __construct(
        private LeagueRepository $leagueRepository,
        private MatchService $matchService,
    ) {}

    /**
     * Summary of getLeagues
     *
     * @param  mixed  $search
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getLeagues(?string $search, int $size = 10, int $page = 1, string $pageName = 'page'): LengthAwarePaginator|Collection
    {
        if ($size === 0) {
            return $this->leagueRepository->getAllLeague();
        }

        return $this->leagueRepository->filter($search, $size, $page, $pageName);
    }

    /**
     * Summary of createLeague
     */
    public function createLeague(string $name, string $country): GameFootballLeague
    {
        return $this->leagueRepository->createLeague($name, $country);
    }

    public function deleteLeague(int $leagueId): bool
    {
        DB::beginTransaction();
        try {
            $delted = $this->leagueRepository->deleteLeague($leagueId);
            if (! $delted) {
                return false;
            }
            $this->matchService->removeLeague($leagueId);
            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return false;
    }
}
