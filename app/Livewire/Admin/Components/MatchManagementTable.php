<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\Match\MatchService;
use App\Domain\SweetAlert\ConfirmationAlert;
use App\Domain\SweetAlert\NormalAlert;
use App\Models\GameFootballMatch;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class MatchManagementTable extends Component
{
    use WithPagination;

    public int $size = 10;

    public string $search = '';

    public ?string $modalName = null;

    #[Computed]
    public function getMatchs()
    {
        /**
         * @var MatchService $leagueService
         */
        $leagueService = app()->make(MatchService::class);

        return $leagueService->getMatches(search: $this->search, page: $this->getPage(), size: $this->size, orderBy: 'desc');
    }

    #[On('refreshMatchManagementTable')]
    public function refreshMatchManagementTable()
    {
        unset($this->getMatchs);
    }

    public function openMatchModal(string $type = '', ?array $match = null)
    {
        $this->modalName = 'match-modal';
        $this->dispatch('pre-open-match-modal', $type, $match);
    }

    public function openMatchResultModal(string $type = '', ?array $match = null)
    {
        $this->dispatch('pre-open-match-result-modal', $type, $match);
    }

    public function deleteMatch(int $matchId)
    {
        $this->dispatch('sweet-alert-confirmation', (new ConfirmationAlert(
            'ต้องการลบแมตการแข่งขันหรือไม่',
            'การลบข้อมูลจะไม่หายไปถาวร',
            'warning',
            __('website.button.delete_button_text'),
            'confirm-delete-match',
            ['matchId' => $matchId]
        ))->toArray());
    }

    #[On('confirm-delete-match')]
    public function confirmDelete(array $data)
    {
        GameFootballMatch::where('id', $data['matchId'])->delete();
        $this->dispatch('sweet-alert', (new NormalAlert(
            __('website.alert.delete_match_success'),
            'success'
        ))->toArray());
    }

    public function render()
    {
        return view('livewire.admin.components.match-management-table', [
            'matches' => $this->getMatchs(),
        ]);
    }
}
