<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\Team\TeamService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TeamSelect extends Component
{
    use WithPagination;

    public ?string $search = '';

    public int $size = 0;

    public ?int $selectTeamId = null;

    public string $fieldName;

    public ?string $modalId = null;

    public ?string $homeTeam = null;

    public ?string $awayTeam = null;

    public function mount(string $fieldName, ?string $modalId = '')
    {
        $this->fieldName = $fieldName;
        $this->{$this->fieldName} = null;
        $this->modalId = $modalId;
    }

    public function boot()
    {
        $this->dispatch("init-select-team-{$this->fieldName}", [
            'isModal' => $this->modalId ? true : false,
            'modalId' => $this->modalId,
            'fieldName' => $this->fieldName,
        ]);
    }

    public function updatedSelectTeamId()
    {
        $this->dispatch(
            'set-team-from-select',
            $this->selectTeamId,
            $this->fieldName
        );
    }

    #[On('set-default-field-{fieldName}')]
    public function setField(?int $id)
    {
        $this->selectTeamId = $id;
        $this->dispatch("trigger-select-change-team-{$this->fieldName}", ['id' => $id, 'field_name' => $this->fieldName]);
    }

    #[Computed]
    public function getTeams(): Collection
    {
        /**
         * @var TeamService $teamService
         */
        $teamService = app()->make(TeamService::class);

        return $teamService->getTeams(search: $this->search, size: $this->size);
    }

    #[On('reset-field-{fieldName}')]
    public function resetField()
    {
        $this->reset([
            'selectTeamId',
            'homeTeam',
            'awayTeam',
        ]);
        $this->dispatch("trigger-select-change-team-{$this->fieldName}", ['id' => null, 'field_name' => $this->fieldName]);
    }

    public function render()
    {
        return view('livewire.admin.components.team-select', [
            'teams' => $this->getTeams(),
        ]);
    }
}
