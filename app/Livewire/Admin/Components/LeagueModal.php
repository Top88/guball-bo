<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\League\LeagueService;
use App\Domain\SweetAlert\ConfirmationAlert;
use App\Domain\SweetAlert\NormalAlert;
use App\Infrastructure\Traits\LivewireComponentTrait;
use Livewire\Attributes\On;
use Livewire\Component;

class LeagueModal extends Component
{
    use LivewireComponentTrait;

    public string $name = '';

    public string $country = '';

    public string $modalType = '';

    public string $modalTypeLabel = '';

    public ?array $league;

    #[On('open-modal')]
    public function openModal(string $type = 'add', ?array $league = null)
    {
        $this->modalType = $type;
        $this->league = $league;
        $this->modalTypeLabel = __('website.general.'.$type);
        // Reset form fields and validation
        $this->resetValidation();
        $this->resetField();
        // Emit event to open the modal
        if ($type === 'edit' && $league !== null) {
            $this->setDataToModal($league);
        }
        $this->dispatch('open-league-modal');
    }

    #[On('close-modal')]
    public function handleCloseModal()
    {
        $this->resetField();
        $this->resetValidation();
    }

    public function resetField()
    {
        $this->reset(['name', 'country']);
    }

    public function submit()
    {
        if ($this->modalType === 'add') {
            $this->createLeague();
        } elseif ($this->modalType === 'edit') {
            // $this->updateUser();
        } else {
            $this->showError();
        }
        $this->dispatch('hide-league-modal');
        $this->dispatch('refreshLeagueManagementTable');
    }

    public function createLeague()
    {
        $this->validate([
            'name' => 'required|string',
            'country' => 'required|string',
        ]);
        /** @var LeagueService $leagueService */
        $leagueService = app()->make(LeagueService::class);
        $leagueService->createLeague(
            $this->name,
            $this->country,
        );
        $this->dispatch('sweet-alert', [
            'type' => 'success',
            'title' => __('website.alert.create_league_success'),
        ]);
    }

    #[On('confirmDeleteLeague')]
    public function deleteLeague(string $leagueId)
    {
        $this->dispatch('sweet-alert-confirmation', (new ConfirmationAlert(
            __('website.alert.delete_league_title'),
            __('website.alert.delete_league_message'),
            'warning',
            __('website.button.delete_button_text'),
            'confirmedDeleteLeagueEvent',
            ['league_id' => $leagueId]
        ))->toArray());
    }

    #[On('confirmedDeleteLeagueEvent')]
    public function confirmedDeleteUser(array $data)
    {
        /** @var LeagueService $leagueManagementService */
        $leagueManagementService = app()->make(LeagueService::class);
        $deleted = $leagueManagementService->deleteLeague($data['league_id']);
        if ($deleted) {
            $this->dispatch('sweet-alert', (new NormalAlert(
                __('website.alert.delete_league_confirmed_title'),
                'success',
            ))->toArray());
            $this->dispatch('refreshLeagueManagementTable');
        } else {
            $this->showError();
        }

    }

    public function render()
    {
        return view('livewire.admin.components.league-modal');
    }
}
