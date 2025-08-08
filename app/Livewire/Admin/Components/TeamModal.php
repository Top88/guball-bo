<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\Team\TeamService;
use App\Domain\SweetAlert\ConfirmationAlert;
use App\Domain\SweetAlert\NormalAlert;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class TeamModal extends Component
{
    public string $modalType = '';

    public string $modalTypeLabel = '';

    public string $teamName = '';

    public ?array $team = [];

    public ?int $leagueId = null;

    public ?int $teamId = null;

    #[On('open-modal')]
    public function openModal(string $type = 'add', ?array $team = null)
    {
        $this->modalType = $type;
        $this->team = $team;
        $this->modalTypeLabel = __("website.general.$type");
        // Reset form fields and validation
        $this->resetValidation();
        $this->resetField();
        // Emit event to open the modal
        if ($type === 'edit' && $team !== null) {
            $this->setDataToModal();
        }
        $this->dispatch('open-team-modal');
    }

    public function setDataToModal()
    {
        $this->teamId = $this->team['id'] ?? null;
        $this->teamName = $this->team['name'] ?? '';
        $this->leagueId = $this->team['league_id'] ?? null;
        $this->dispatch('set-default-field-league', $this->leagueId);
    }

    #[On('set-league-from-select')]
    public function getSelectLeague(?int $leagueId)
    {
        $this->leagueId = $leagueId;
    }

    public function resetField()
    {
        $this->reset(['teamId', 'teamName', 'leagueId']);
        $this->dispatch('reset-field');
    }

    public function submit()
    {
        if ($this->modalType === 'add') {
            $this->createTeam();
        } elseif ($this->modalType === 'edit') {
            $this->updateUser();
        } else {
            $this->dispatch('sweet-alert', [
                'type' => 'error',
                'title' => __('website.alert.error'),
                'text' => __('website.alert.error_message'),
            ]);
        }
        $this->dispatch('hide-team-modal');
        $this->dispatch('refreshTeamManagementTable');
    }

    public function createTeam()
    {
        $this->validate([
            'teamName' => ['required', 'string', 'unique:game_football_team,name'],
            'leagueId' => ['nullable', 'numeric', 'exists:game_football_league,id'],
        ]);
        /**
         * @var TeamService $teamService
         */
        $teamService = app()->make(TeamService::class);
        $teamService->createTeam(
            $this->teamName,
            $this->leagueId,
        );
        $this->dispatch('sweet-alert', [
            'type' => 'success',
            'title' => __('website.alert.create_team_success'),
        ]);
    }

    public function updateUser()
    {
        /** @var TeamService $teamService */
        $teamService = app()->make(TeamService::class);
        $this->validate([
            'teamName' => ['required', 'string', Rule::unique('game_football_team', 'name')->ignore($this->teamId)],
            'leagueId' => ['nullable', 'numeric', 'exists:game_football_league,id'],
        ]);
        $teamService->updateTeam(
            $this->teamId,
            $this->teamName,
            $this->leagueId,
        );

        $this->dispatch('sweet-alert', (new NormalAlert(__('website.alert.edit_team_success'), 'success'))->toArray());
    }

    #[On('confirmDeleteTeam')]
    public function deleteLeague(string $teamId)
    {
        $this->dispatch('sweet-alert-confirmation', (new ConfirmationAlert(
            __('website.alert.delete_team_title'),
            __('website.alert.delete_team_message'),
            'warning',
            __('website.button.delete_button_text'),
            'confirmedDeleteTeamEvent',
            ['team_id' => $teamId]
        ))->toArray());
    }

    #[On('confirmedDeleteTeamEvent')]
    public function confirmedDeleteUser(array $data)
    {
        /** @var TeamService $teamManagementService */
        $teamManagementService = app()->make(TeamService::class);
        $teamManagementService->deleteTeam($data['team_id']);
        $this->dispatch('sweet-alert', (new NormalAlert(
            __('website.alert.delete_team_confirmed_title'),
            'success',
        ))->toArray());
        $this->dispatch('refreshTeamManagementTable');
    }

    public function render()
    {
        return view('livewire.admin.components.team-modal');
    }
}
