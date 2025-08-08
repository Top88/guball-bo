<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\League\LeagueService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class LeagueSelect extends Component
{
    use WithPagination;

    public ?string $search = '';

    public int $size = 0;

    public ?int $selectLeagueId = null;

    public ?string $modalId = null;

    public string $fieldName = '';

    public function mount(string $fieldName, ?string $modalId = '')
    {
        $this->modalId = $modalId;
        $this->fieldName = $fieldName;
    }

    public function boot()
    {
        $this->dispatch('init-select', ['isModal' => $this->modalId ? true : false, 'modalId' => $this->modalId]);
    }

    public function updatedSelectLeagueId()
    {
        $this->dispatch('set-league-from-select', $this->selectLeagueId);
    }

    #[On('set-default-field-{fieldName}')]
    public function setField(?int $id)
    {
        $this->selectLeagueId = $id;
        $this->dispatch('trigger-select-change-'.$this->fieldName, ['id' => $id, 'fieldName' => $this->fieldName]);
    }

    #[Computed]
    public function getLeagues(): Collection
    {
        /**
         * @var LeagueService $leagueService
         */
        $leagueService = app()->make(LeagueService::class);

        return $leagueService->getLeagues(search: $this->search, page: $this->getPage(), size: $this->size);
    }

    #[On('reset-field-{fieldName}')]
    public function resetField()
    {
        $this->reset(['selectLeagueId']);
        $this->dispatch('trigger-select-change-'.$this->fieldName, ['id' => null, 'fieldName' => $this->fieldName]);
    }

    public function render()
    {
        return view('livewire.admin.components.league-select', [
            'leagues' => $this->getLeagues(),
        ]);
    }
}
