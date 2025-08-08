<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\Match\TeamMatchResult;
use App\Domain\Football\Match\WinType;
use App\Exceptions\MatchNotFoundException;
use App\Infrastructure\Matches\Enums\MatchStatus;
use App\Jobs\RewardUserWinPredictionJob;
use App\Models\GameFootballMatch;
use App\Models\GameFootballMatchResult;
use App\Models\GameFootballTeam;
use DB;
use Livewire\Attributes\On;
use Livewire\Component;
use Log;

class MatchResultModal extends Component
{
    const DEFAULT_GAME_MINUTE = 90;

    public ?array $matchResult = [];

    public string $modalType = '';

    public string $modalTypeLabel = '';

    public ?int $winTeamSelectedId = null;

    public ?string $homeTeam = null;

    public ?string $awayTeam = null;

    public ?int $homeTeamId = null;

    public ?int $awayTeamId = null;

    public ?int $winTeamId = null;

    public array $winTypeList = [];

    public ?string $winType = null;

    public int $homeTeamGoal = 0;

    public int $awayTeamGoal = 0;

    public ?int $matchId = null;

    public int $gameTimeMinute = self::DEFAULT_GAME_MINUTE;

    public ?string $teamMatchResult = null;

    public function mount()
    {
        $this->winTypeList = array_values(array_column(WinType::cases(), 'value'));
    }

    #[On('pre-open-match-result-modal')]
    public function openModal(string $type = 'add', ?array $matchResult = null)
    {
        $this->modalType = $type;
        $this->matchResult = $matchResult;
        $this->modalTypeLabel = __("website.general.$type");
        // Reset form fields and validation
        $this->resetValidation();
        $this->resetField();
        // Emit event to open the modal
        if ($type === 'edit' && $matchResult !== null) {
            $this->setDataToModal($matchResult);
        }
        $this->dispatch('open-match-result-modal');
    }

    public function setDataToModal(?array $matchResult)
    {
        $this->matchId = $matchResult['id'];
        $this->homeTeamId = $matchResult['home_team_id'];
        $this->homeTeam = GameFootballTeam::find($matchResult['home_team_id'])?->name;
        $this->awayTeamId = $matchResult['away_team_id'];
        $this->awayTeam = GameFootballTeam::find($matchResult['away_team_id'])?->name;
    }

    public function winTeamSelected(int $winTeamId, string $teamMatchResult)
    {
        $this->winTeamId = $winTeamId;
        $this->teamMatchResult = TeamMatchResult::tryFrom($teamMatchResult)?->value;
    }

    public function draw()
    {
        $this->winTeamId = null;
        $this->teamMatchResult = TeamMatchResult::DRAW->value;
        $this->winType = null;
    }

    public function submitMatchresult()
    {
        $this->validate([
            'winTeamId' => ['required_if:teamMatchResult,home,away'],
            'teamMatchResult' => ['required'],
            'homeTeamGoal' => ['required'],
            'awayTeamGoal' => ['required'],
            'matchId' => ['required', 'unique:game_football_match_result,match_id'],
            'winType' => ['required_if:teamMatchResult,home,away'],
        ], [
            'unique' => ':attribute ประกาศผลการแข่งไปแล้ว',
        ], [
            'matchId' => 'รายการแข่งนี้',
        ]);
        DB::beginTransaction();
        try {
            $updated = GameFootballMatch::where('id', $this->matchId)
                ->where('status', MatchStatus::ACTIVE->value)
                ->update([
                    'status' => MatchStatus::FINISHED->value,
                ]);
            if (! $updated) {
                throw new \Exception("Can't patch match status");
            }
            GameFootballMatchResult::create([
                'match_id' => $this->matchId,
                'home_team_goal' => $this->homeTeamGoal,
                'away_team_goal' => $this->awayTeamGoal,
                'team_match_result' => $this->teamMatchResult,
                'team_win_id' => $this->winTeamId,
                'game_time_minute' => $this->gameTimeMinute,
                'win_type' => $this->winType,
            ]);
            DB::commit();

            RewardUserWinPredictionJob::dispatch($this->matchId, auth()->user()->id);
            $this->dispatch('refreshMatchManagementTable');
            $this->dispatch('hide-match-result-modal');
            $this->resetField();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function resetField()
    {
        $this->gameTimeMinute = self::DEFAULT_GAME_MINUTE;
        $this->reset(
            'homeTeamId',
            'homeTeam',
            'awayTeamId',
            'awayTeam',
            'winTeamId',
            'teamMatchResult',
            'matchId',
            'modalType',
            'matchResult',
            'modalTypeLabel',
            'homeTeamGoal',
            'awayTeamGoal',
            'gameTimeMinute',
            'winType'
        );
    }

    public function render()
    {
        return view('livewire.admin.components.match-result-modal', [
            'winTypeList' => $this->winTypeList,
        ]);
    }

    public function exception($e, callable $stopPropagation)
    {
        if ($e instanceof MatchNotFoundException) {
            $this->dispatch('sweet-alert', [
                'type' => 'error',
                'title' => __('website.alert.something_went_wrong'),
            ]);
            $stopPropagation();
        }
    }
}
