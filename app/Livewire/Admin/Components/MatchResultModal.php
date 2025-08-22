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
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

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
    public ?string $teamMatchResult = null; // home | away | draw

    public function mount()
    {
        $this->winTypeList = array_map(fn($c) => $c->value, WinType::cases());
    }

    #[On('pre-open-match-result-modal')]
    public function openModal(string $type = 'add', ?array $matchResult = null)
    {
        $this->modalType = $type;
        $this->matchResult = $matchResult;
        $this->modalTypeLabel = __("website.general.$type");

        $this->resetValidation();
        $this->resetField();

        if ($type === 'edit' && $matchResult !== null) {
            $this->setDataToModal($matchResult);
        }

        $this->dispatch('open-match-result-modal');
    }

    public function setDataToModal(?array $matchResult)
    {
        $this->matchId    = $matchResult['id'] ?? null;
        $this->homeTeamId = $matchResult['home_team_id'] ?? null;
        $this->awayTeamId = $matchResult['away_team_id'] ?? null;

        $this->homeTeam   = $this->homeTeamId ? GameFootballTeam::find($this->homeTeamId)?->name : null;
        $this->awayTeam   = $this->awayTeamId ? GameFootballTeam::find($this->awayTeamId)?->name : null;

        if ($this->matchId) {
            $existing = GameFootballMatchResult::where('match_id', $this->matchId)->first();
            if ($existing) {
                $this->homeTeamGoal    = (int) ($existing->home_team_goal ?? 0);
                $this->awayTeamGoal    = (int) ($existing->away_team_goal ?? 0);
                $this->teamMatchResult = $existing->team_match_result ?: null;
                $this->winTeamId       = $existing->team_win_id ?: null;
                $this->winType         = $existing->win_type ?: null;
                $this->gameTimeMinute  = (int) ($existing->game_time_minute ?? self::DEFAULT_GAME_MINUTE);
            }
        }
    }

    public function winTeamSelected(int $winTeamId, string $teamMatchResult)
    {
        $this->winTeamId       = $winTeamId;
        $this->teamMatchResult = TeamMatchResult::tryFrom($teamMatchResult)?->value;
    }

    public function draw()
    {
        $this->winTeamId = null;
        $this->teamMatchResult = TeamMatchResult::DRAW->value; // 'draw'
        $this->winType = null;
    }

    public function submitMatchresult()
    {
        if (in_array($this->teamMatchResult, ['home','away'], true) && empty($this->winTeamId)) {
            $this->winTeamId = $this->teamMatchResult === 'home' ? $this->homeTeamId : $this->awayTeamId;
        }

        $matchTable  = (new GameFootballMatch())->getTable();          // ✅ ใช้จากโมเดล
        $resultTable = (new GameFootballMatchResult())->getTable();    // ✅ ใช้จากโมเดล

        $rules = [
            'matchId'         => ['required', Rule::exists($matchTable, 'id')], // ✅ แทน exists:game_football_matches,id
            'teamMatchResult' => ['required', Rule::in(['home','away','draw'])],
            'homeTeamGoal'    => ['required', 'integer', 'min:0'],
            'awayTeamGoal'    => ['required', 'integer', 'min:0'],
            'gameTimeMinute'  => ['nullable', 'integer', 'min:1', 'max:180'],
            'winType'         => ['nullable', Rule::in($this->winTypeList)],
        ];
        if (in_array($this->teamMatchResult, ['home','away'], true)) {
            $rules['winTeamId'] = ['required', 'integer'];
            if (Schema::hasColumn($resultTable, 'win_type')) {
                $rules['winType'] = ['required', Rule::in($this->winTypeList)];
            }
        }

        $this->validate($rules);

        DB::beginTransaction();
        try {
            $match = GameFootballMatch::lockForUpdate()->findOrFail($this->matchId);

            $match->status = MatchStatus::FINISHED->value;

            if (Schema::hasColumn($match->getTable(), 'home_team_goal')) {
                $match->home_team_goal = (int) $this->homeTeamGoal;
            }
            if (Schema::hasColumn($match->getTable(), 'away_team_goal')) {
                $match->away_team_goal = (int) $this->awayTeamGoal;
            }
            $match->save();

            $payload = [
                'home_team_goal'    => $this->homeTeamGoal,
                'away_team_goal'    => $this->awayTeamGoal,
                'team_match_result' => $this->teamMatchResult,
            ];
            if (Schema::hasColumn($resultTable, 'team_win_id')) {
                $payload['team_win_id'] = in_array($this->teamMatchResult, ['home','away'], true) ? $this->winTeamId : null;
            }
            if (Schema::hasColumn($resultTable, 'game_time_minute')) {
                $payload['game_time_minute'] = $this->gameTimeMinute ?: self::DEFAULT_GAME_MINUTE;
            }
            if (Schema::hasColumn($resultTable, 'win_type')) {
                $payload['win_type'] = in_array($this->teamMatchResult, ['home','away'], true) ? $this->winType : null;
            }

            GameFootballMatchResult::updateOrCreate(
                ['match_id' => $this->matchId],
                $payload
            );

            DB::commit();

            try {
                $matchDate = Carbon::parse(
                    $match->match_datetime
                        ?? $match->match_at
                        ?? $match->start_at
                        ?? $match->date
                        ?? $match->match_date
                        ?? $match->created_at
                )->toDateString();

                try { Artisan::call('app:sync-user-statistic', ['--from' => $matchDate, '--to' => $matchDate]); } catch (\Throwable $e) {}
                try { Artisan::call('app:backfill-step-bonus', ['--from' => $matchDate, '--to' => $matchDate, '--force' => true]); } catch (\Throwable $e) {}
                try { Artisan::call('cache:clear'); Artisan::call('view:clear'); } catch (\Throwable $e) {}
            } catch (\Throwable $e) {
                Log::warning('[MatchResultModal] post-commit: '.$e->getMessage());
            }

            try { RewardUserWinPredictionJob::dispatch($this->matchId, Auth::id()); } catch (\Throwable $e) {}

            $this->dispatch('refreshMatchManagementTable');
            $this->dispatch('hide-match-result-modal');
            $this->resetField();
            $this->dispatch('sweet-alert', ['type' => 'success', 'title' => __('website.alert.saved_success')]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[MatchResultModal] submitMatchresult error: '.$e->getMessage());
            $this->dispatch('sweet-alert', ['type' => 'error', 'title' => __('website.alert.something_went_wrong')]);
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
