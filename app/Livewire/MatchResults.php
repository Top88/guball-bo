<?php

namespace App\Livewire;

use App\Domain\Football\Match\MatchService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class MatchResults extends Component
{
    public string $startDate;
    public string $endDate;

    public function __construct(private readonly MatchService $matchService)
    {
        parent::__construct();
    }

    public function mount(?string $startDate = null, ?string $endDate = null): void
    {
        $this->startDate = $startDate
            ?? config('settings.competition_start_date', Carbon::now()->subDays(7)->toDateString());

        $this->endDate = $endDate
            ?? config('settings.competition_end_date', Carbon::now()->toDateString());
    }

    #[Computed]
    public function results(): Collection
    {
        return $this->matchService->getFinishedMatchesByDateRange(
            $this->startDate,
            $this->endDate,
        );
    }

    public function render()
    {
        return view('livewire.match-results', [
            'resultsByDate' => $this->results(),
        ]);
    }
}
