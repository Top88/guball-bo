<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\League\LeagueService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class LeagueManagementTable extends Component
{
    use WithPagination;

    #[Url]
    #[Validate('integer|min:1')]
    public int $size = 10;

    #[Url]
    #[Validate('nullable|string|min:2')]
    public ?string $search = '';

    #[Computed]
    public function getLeagues()
    {
        /**
         * @var LeagueService $leagueService
         */
        $leagueService = app()->make(LeagueService::class);

        return $leagueService->getLeagues(search: $this->search, page: $this->getPage(), size: $this->size);
    }

    public function searchData()
    {
        $this->validate();
        $this->resetPage();
    }

    public function openLeagueModal(string $type = '', ?array $user = null)
    {
        $this->dispatch('open-modal', $type, $user);
    }

    #[On('refreshLeagueManagementTable')]
    public function refreshLeagueManagementTable()
    {
        unset($this->getLeagues);
    }

    public function deleteLeague(string $leagueId)
    {
        $this->dispatch('confirmDeleteLeague', $leagueId);
    }

    public function render()
    {
        return view('livewire.admin.components.league-management-table', [
            'leagues' => $this->getLeagues(),
        ]);
    }
}
