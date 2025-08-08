<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\Match\MatchService;
use App\Domain\Football\RateType;
use App\Domain\SweetAlert\NormalAlert;
use App\Traits\HelpersTrait;
use Carbon\Carbon;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class MatchModal extends Component
{
    use HelpersTrait;

    /**
     * @var string
     */
    public string $modalType = '';

    /**
     * @var string
     */
    public string $modalTypeLabel = '';

    /**
     * @var string|null
     */
    public ?string $matchDate = null;

    /**
     * @var string|null
     */
    public ?string $matchTime = null;

    /**
     * @var int|null
     */
    public ?int $leagueId = null;

    /**
     * @var string|null
     */
    public ?string $homeTeam = null;

    /**
     * @var string|null
     */
    public ?string $awayTeam = null;

    /**
     * @var string|null
     */
    public ?string $rateType = null;

    /**
     * @var array|null
     */
    public ?array $match;

    /**
     * @var int|null
     */
    public ?int $id = null;

    /**
     * @var array|string[]
     */
    public array $rates = [
        'เลือกอัตราต่อรอง',
        'เสมอ',
        '0/0.5',
        '0.5',
        '0.5/1',
        '1',
        '1/1.5',
        '1.5',
        '1.5/2',
        '2',
        '2.5',
        '2.5/3',
        '3',
        '3/3.5',
        '3.5',
        '4',
        '4/4.5',
        '4.5',
        '5',
        '5/5.5',
        '5.5',
        '6',
    ];

    /**
     * @var string
     */
    public string $selectedRate = "0";
    /**
     * @var string
     */
    public string $selectedFavorite = "0";

    /**
     * @var array|string[]
     */
    public array $favoriteType = [null => 'เลือกทีมต่อ', 'same' => 'เท่ากัน','home'=>'เจ้าบ้าน', 'away' =>'เยือน'];

    /**
     * @param string $type
     * @param array|null $match
     * @return void
     */
    #[On('pre-open-match-modal')]
    public function openModal(string $type = 'add', ?array $match = null): void
    {
        $this->modalType = $type;
        $this->match = $match;
        $this->modalTypeLabel = __("website.general.$type");
        // Reset form fields and validation
        $this->resetValidation();
        $this->resetField();
        // Emit event to open the modal
        if ($type === 'edit' && $match !== null) {
            $this->setDataToModal($match);
        }
        $this->dispatch('open-match-modal');
    }

    /**
     * @param int|null $teamId
     * @param string $fieldName
     * @return void
     */
    #[On('set-team-from-select')]
    public function getSelectTeam(?int $teamId, string $fieldName): void
    {
        if ($fieldName === 'homeTeam') {
            $this->homeTeam = $teamId;
        } elseif ($fieldName === 'awayTeam') {
            $this->awayTeam = $teamId;
        }
    }

    /**
     * @param string|null $rateTypeName
     * @return void
     */
    #[On('set-rate-type-from-select')]
    public function getSelectRateType(?string $rateTypeName): void
    {
        $this->rateType = $rateTypeName;
    }

    /**
     * @param int|null $leagueId
     * @return void
     */
    #[On('set-league-from-select')]
    public function getSelectLeague(?int $leagueId): void
    {
        $this->leagueId = $leagueId;
    }

    /**
     * @return void
     */
    public function resetField()
    {
        $this->reset([
            'matchDate',
            'matchTime',
            'homeTeam',
            'awayTeam',
            'rateType',
            'match',
            'leagueId',
        ]);
        $this->dispatch('reset-field-league');
        $this->dispatch('reset-field-homeTeam');
        $this->dispatch('reset-field-awayTeam');
        $this->dispatch('reset-field-rateType');
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function submit(): void
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
        $this->dispatch('hide-match-modal');
        $this->dispatch('refreshMatchManagementTable');
        $this->resetField();
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function createTeam(): void
    {
        $this->validate([
            'matchDate' => 'required|string',
            'matchTime' => 'required|string',
            'leagueId' => 'nullable|numeric|exists:game_football_league,id',
            'homeTeam' => 'required|numeric|exists:game_football_team,id',
            'awayTeam' => 'required|numeric|exists:game_football_team,id|different:homeTeam',
            'selectedRate' => 'required|not_in:"0"',
            'selectedFavorite' => 'required'
        ]);
        /** @var MatchService $matchService */
        $matchService = app()->make(MatchService::class);
        $matchService->createMatch(
            $this->matchDate,
            self::enrichTime($this->matchTime),
            $this->leagueId,
            $this->homeTeam,
            $this->awayTeam,
            'normal',
            $this->rates[$this->selectedRate],
            $this->selectedFavorite
        );

        $this->dispatch('sweet-alert', [
            'type' => 'success',
            'title' => __('website.alert.create_match_success'),
        ]);
    }

    /**
     * @return void
     * @throws BindingResolutionException
     */
    public function updateUser(): void
    {
        /** @var MatchService $matchService */
        $matchService = app()->make(MatchService::class);
        $this->validate([
            'id' => 'required',
            'matchDate' => 'required|string',
            'matchTime' => 'required|string',
            'leagueId' => 'nullable|numeric|exists:game_football_league,id',
            'homeTeam' => 'required|numeric|exists:game_football_team,id',
            'awayTeam' => 'required|numeric|exists:game_football_team,id|different:homeTeam',
            'rateType' => ['required', 'string', Rule::in(RateType::allCases())],
        ]);
        $matchService->updateMatch(
            $this->id,
            $this->matchDate,
            self::enrichTime($this->matchTime),
            $this->leagueId,
            $this->homeTeam,
            $this->awayTeam,
            $this->rateType,
        );

        $this->dispatch('sweet-alert', (new NormalAlert(__('website.alert.edit_match_success'), 'success'))->toArray());
    }

    /**
     * @param array|null $match
     * @return void
     */
    public function setDataToModal(?array $match): void
    {
        $mathDate = Carbon::createFromFormat('Y-m-d H:i:s', $match['match_date']);
        $this->id = $match['id'];
        $this->matchDate = $mathDate->toDateString();
        $this->matchTime = $mathDate->hour.':'.self::fillTime($mathDate->minute);
        $this->leagueId = $match['league_id'];
        $this->homeTeam = $match['home_team_id'];
        $this->awayTeam = $match['away_team_id'];
        $this->rateType = $match['rate_type'];
        $this->updateSubComponents();
    }

    /**
     * @return void
     */
    public function updateSubComponents(): void
    {
        $this->dispatch('set-default-field-league', $this->leagueId);
        $this->dispatch('set-default-field-homeTeam', $this->homeTeam);
        $this->dispatch('set-default-field-awayTeam', $this->awayTeam);
        $this->dispatch('set-default-field-rateType', $this->rateType);
    }

    /**
     * @return void
     */
    #[On('close-modal')]
    public function handleCloseModal(): void
    {
        $this->resetField();
        $this->resetValidation();
    }

    /**
     * @param $data
     * @return void
     */
    #[On('change-rate')]
    public function changeRate($data): void
    {
        $this->selectedRate = $data['data'];
    }

    /**
     * @param $data
     * @return void
     */
    #[On('change-favorite')]
    public function changeFavorite($data): void
    {
        $this->selectedFavorite = $data['data'];
    }

    /**
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        return view('livewire.admin.components.match-modal');
    }
}
