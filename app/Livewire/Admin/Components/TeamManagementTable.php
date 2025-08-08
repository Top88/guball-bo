<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\Team\TeamService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class TeamManagementTable extends Component
{
    use WithPagination;

    #[Url]
    #[Validate('integer|min:1')]
    public int $size = 10;

    #[Url]
    #[Validate('nullable|string|min:2')]
    public ?string $search = '';

    #[Computed]
    public function getTeams()
    {
        /**
         * @var TeamService $teamService
         */
        $teamService = app()->make(TeamService::class);

        return $teamService->getTeams(search: $this->search, page: $this->getPage(), size: $this->size);
    }

    public function searchData()
    {
        $this->validate();
        $this->resetPage();
    }

    public function openTeamModal(string $type = '', ?array $match = null)
    {
        $this->dispatch('open-modal', $type, $match);
    }

    #[On('refreshTeamManagementTable')]
    public function refreshTeamManagementTable()
    {
        unset($this->getTeams);
    }

    public function deleteTeam(string $teamId)
    {
        $this->dispatch('confirmDeleteTeam', $teamId);
    }

    public function render()
    {
        return view('livewire.admin.components.team-management-table', [
            'teams' => $this->getTeams(),
        ]);
    }
}
