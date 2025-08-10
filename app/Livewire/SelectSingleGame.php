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

class SelectSingleGame extends Component
{
    public array $predictions = [];

    public bool $isSelectable = false;

    public function mount(): void
    {
        $this->isSelectable = $this->setValidTime() && $this->checkPredicted();
    }

    public function setValidTime(): bool
    {
        $start = explode(':', (string) config('settings.predic_time_period_start', '12:00'));
        $end   = explode(':', (string) config('settings.predic_time_period_end',   '23:59'));

        $startTime = Carbon::createFromTime((int) $start[0], (int) $start[1], 0);
        $endTime   = Carbon::createFromTime((int) $end[0],   (int) $end[1],   0);

        return Carbon::now()->between($startTime, $endTime);
    }

    public function checkPredicted(): bool
    {
        $start = explode(':', (string) config('settings.predic_time_period_start', '12:00'));
        $end   = explode(':', (string) config('settings.predic_time_period_end',   '23:59'));

        $startTime = Carbon::createFromTime((int) $start[0], (int) $start[1], 0);
        $endTime   = Carbon::createFromTime((int) $end[0],   (int) $end[1],   0);

        $predicted = GameFootballPrediction::query()
            ->where('user_id', Auth::id())
            ->where('type', 'single')
            ->whereBetween('created_at', [$startTime, $endTime])
            ->first();

        if ($predicted !== null) {
            $this->dispatch('open-alert-predicted-single-modal');
        }

        return $predicted === null;
    }

    #[Computed(true, 60, true)]
    public function matchList(): array|Collection|\Illuminate\Support\Collection
    {
        $now = Carbon::now();

        // แก้เป็น setTimeFromTimeString ให้ตรงชนิด
        $settingTime = Carbon::now()->setTimeFromTimeString(
            (string) config('settings.match_time_allow_to_start_predic', '10:00')
        );
        $startAllowPredicTeam = $now->greaterThan($settingTime) ? $now : $settingTime->copy();

        $endAllowPredicTeam = Carbon::now()->addDay()->setTimeFromTimeString(
            (string) config('settings.match_time_allow_to_end_predic', '09:59')
        );

        return GameFootballMatch::with(['league', 'homeTeam', 'awayTeam', 'prediction'])
            ->where('status', MatchStatus::active)
            ->where('match_date', '>',  $startAllowPredicTeam->toDateTimeString())
            ->where('match_date', '<=', $endAllowPredicTeam->toDateTimeString())
            ->whereDoesntHave('prediction', function (Builder $query) {
                $query->where('user_id', Auth::id())->where('type', 'single');
            })
            ->orderBy('match_date')
            ->get()
            ->groupBy('league_id');
    }

    public function updatedPredictions($value, $key): void
    {
        if (count(array_filter($this->predictions)) > 1) {
            $this->predictions[$key] = null;
            session()->flash('validate', 'เลือกได้เพียง 1 คู่เท่านั้น');
        }
    }

    public function resetPredictions(): void
    {
        $this->predictions = [];
    }

    public function predicMatch(): void
    {
        // กันคลิกซ้ำจากฝั่งเซิร์ฟเวอร์
        if (!$this->isSelectable) {
            $this->dispatch('open-alert-predicted-single-modal');
            return;
        }

        $validated = Validator::make(['predictions' => $this->predictions], [
            'predictions'   => 'required|array|min:1|max:1',
            'predictions.*' => 'required|integer|exists:game_football_team,id',
        ], [
            'predictions.min' => 'ต้องเลือกอย่างน้อย :min รายการ',
            'predictions.max' => 'เลือกได้ไม่เกิน :max รายการ',
        ]);

        if ($validated->fails()) {
            session()->flash('validate', $validated->errors()->first());
            return;
        }

        try {
            $predictionService    = app()->make(PredictionService::class);
            $predictionCollection = new PredictionCollection();

            foreach ($this->predictions as $matchId => $teamId) {
                $predictionCollection->add(new Prediction(
                    Auth::id(),
                    $matchId,
                    $teamId,
                    CurrencyType::SILVER_COIN,
                    (float) config('settings.prediction_cost', 0),
                    'single' // type
                ));
            }

            // บันทึกผลการทาย
            $predictionService->matchPrediction(Auth::id(), $predictionCollection, 'single');

            // ✅ รีเฟรชหน้า 1 ครั้ง (ง่ายที่สุด)
            $this->dispatch('prediction-success-refresh');

            // ปิดปุ่มและ sync สถานะ (กัน submit ซ้ำ)
            $this->isSelectable = false;
            $this->isSelectable = $this->setValidTime() && $this->checkPredicted();

            // (ถ้าจะโชว์ modal ด้วยก็ยังได้)
            $this->dispatch('open-success-prediction-modal');
            $this->dispatch('updated-user-data');

            // ล้างค่าหน้า
            $this->resetPredictions();
            unset($this->matchList);
        } catch (\Exception $th) {
            Log::error($th);
            $this->dispatch('sweet-alert', (new NormalAlert(
                'ไม่สามารถบันทึกการทายผลได้',
                'error',
            ))->toArray());
        }
    }

    public function render()
    {
        return view('livewire.select-single-game');
    }
}
