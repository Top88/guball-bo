<?php

namespace App\Livewire;

use App\Domain\Football\Match\MatchStatus;
use App\Domain\Prediction\CurrencyType;
use App\Domain\Prediction\Prediction;
use App\Domain\Prediction\PredictionCollection;
use App\Domain\Prediction\PredictionService;
use App\Domain\SweetAlert\NormalAlert;
use App\Models\GameFootballMatch;
use App\Models\GameFootballPrediction;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Log;
use Validator;

class SelectGame extends Component
{
    public array $predictions = [];

    public bool $isSelectable = false;

    public function mount(): void
    {
        $this->isSelectable = $this->setValidTime() && $this->checkPredicable();
    }

    public function setValidTime(): bool
    {
        $startTime = explode(':', config('settings.predic_time_period_start'));
        $endTime = explode(':', config('settings.predic_time_period_end'));
        $startTime = Carbon::createFromTime($startTime[0], $startTime[1], 0);
        $endTime = Carbon::createFromTime($endTime[0], $endTime[1], 0);
        return Carbon::now()->between($startTime, $endTime);
    }

    public function checkPredicable(): bool
    {
        $startTime = explode(':', config('settings.predic_time_period_start'));
        $endTime = explode(':', config('settings.predic_time_period_end'));
        $startTime = Carbon::createFromTime($startTime[0], $startTime[1], 0);
        $endTime = Carbon::createFromTime($endTime[0], $endTime[1], 0);
        $predicted = GameFootballPrediction::query()->where('user_id', Auth::id())
                ->where('type', 'step')
                ->where('created_at', '>=', $startTime->toDateTimeString())
                ->where('created_at', '<=', $endTime->toDateTimeString())
                ->first();
        if (null !== $predicted) {
            $this->dispatch('open-alert-predicted-modal');
        }
        return null === $predicted;
    }

    #[Computed(true, 60, true)]
    public function matchList(): array|Collection|\Illuminate\Support\Collection
    {
        $now = Carbon::now();
        $settingTime = Carbon::now()->setTimeFrom(config('settings.match_time_allow_to_start_predic', '10:00'));
        $startAllowPredicTeam = $now->greaterThan($settingTime) ? $now : $settingTime->copy();
        $endAllowPredicTeam = Carbon::now()->addDay()->setTimeFrom(config('settings.match_time_allow_to_end_predic', '09:59'));
        return GameFootballMatch::with(['league', 'homeTeam', 'awayTeam', 'prediction'])
            ->where('status', MatchStatus::active)
            ->where('match_date', '>', $startAllowPredicTeam->toDateTimeString())
            ->where('match_date', '<=', $endAllowPredicTeam->toDateTimeString())
            ->whereDoesntHave('prediction', function (Builder $query) {
                $query->where('user_id', Auth::user()->id)
                      ->where('type', 'step');
            })
            ->orderBy('match_date')
            ->get()
            ->groupBy('league_id');
    }

    public function updatedPredictions($value, $key): void
    {
        $selectedCount = count(array_filter($this->predictions));

        if ($selectedCount > config('settings.max_prediction_per_time')) {
            $this->predictions[$key] = null;
            session()->flash('validate', __('validation.custom.predictions.max', ['value' => config('settings.max_prediction_per_time')]));
        }
    }

    public function resetPredictions(): void
    {
        $this->predictions = [];
    }

    public function predicMatch(): void
    {
        $validated = Validator::make(['predictions' => $this->predictions], [
            'predictions' => 'required|array|min:3|max:3',
            'predictions.*' => 'required|integer|exists:game_football_team,id',
        ], [
            'predictions' => [
                'min' => 'ต้องเลือกให้ครบ :min รายการ',
                'max' => 'เลือกได้ไม่เกิน'
            ]
        ]);
        if ($validated->fails()) {
            session()->flash('validate', $validated->errors()->first());
            return;
        }

        try {
            $predictionService = app()->make(PredictionService::class);
            $predictionCollection = new PredictionCollection();
            foreach ($this->predictions as $key => $value) {
                $predictionEntity = new Prediction(
                    Auth::user()->id,
                    $key,
                    $value,
                    CurrencyType::SILVER_COIN,
                    config('settings.prediction_cost'),
                    'step'
                );
                $predictionCollection->add($predictionEntity);
            }
            $predictionService->matchPrediction(Auth::user()->id, $predictionCollection);
            $this->dispatch('open-success-prediction-modal');
            $this->dispatch('updated-user-data');
            $this->resetPredictions();
            unset($this->matchList);
        } catch (\Exception $th) {
            Log::error($th);
            $this->dispatch('sweet-alert', (new NormalAlert(
                $th->getMessage(),
                'error',
            ))->toArray());
        }
    }

    public function render()
    {
        return view('livewire.select-game');
    }
}
