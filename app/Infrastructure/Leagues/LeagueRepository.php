<?php

namespace App\Infrastructure\Leagues;

use App\Infrastructure\Leagues\Enums\LeagueStatus;
use App\Models\GameFootballLeague;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LeagueRepository
{
    /**
     * Summary of __construct
     */
    public function __construct(
        private GameFootballLeague $league,
    ) {}

    public function getAllLeague(): Collection
    {
        return $this->league->all();
    }

    /**
     * Summary of filter
     *
     * @param  mixed  $search
     */
    public function filter(?string $search, int $size = 10, int $page = 1, string $pageName = 'page'): LengthAwarePaginator
    {
        return $this->league->search($search)->paginate($size, ['*'], $pageName, $page);
    }

    public function createLeague(string $name, string $country): GameFootballLeague
    {
        return $this->league->create([
            'name' => $name,
            'country' => $country,
            'status' => LeagueStatus::ACTIVE->value,
        ]);
    }

    public function deleteLeague(int $id): bool
    {
        return $this->league->find($id)->delete();
    }
}
