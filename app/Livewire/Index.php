<?php

namespace App\Livewire;

use App\Models\GameFootballMatch;
use App\Models\GameFootballPrediction;
use App\Models\GameFootballTeam;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    private float $winGetCoin = 0;

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->winGetCoin = config('settings.win_get_coin', 0);
    }

    /**
     * @return mixed
     */
    #[Computed(true, 3600, true)]
    public function mostPredicTeam()
    {
        $startTime = explode(':', config('settings.predic_time_period_start'));
        $endTime = explode(':', config('settings.predic_time_period_end'));
        return GameFootballTeam::query()
            ->withCount([
                'prediction' => function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('created_at', [
                        Carbon::now()->setTime($startTime[0], $startTime[1])
                            ->toDateTimeString(),
                        Carbon::now()->setTime($endTime[0], $endTime[1])
                            ->toDateTimeString(),
                    ]);
                },
            ])
            ->orderBy('prediction_count', 'desc')
            ->limit(3)
            ->get();
    }

    /**
     * @return mixed
     */
    #[Computed(true, 3600, true)]
    public function sianAccurateWeek()
    {
        $periodStart = Carbon::now()->subDays(7)->startOfDay();
        $periodEnd = Carbon::now()->endOfDay();
        $totalMatch = $this->getTotalMatch(
            $periodStart->copy(),
            $periodEnd->copy()
        );
        return GameFootballPrediction::query()->select(
            'user_id',
            DB::raw('COUNT(id) as times'),
            DB::raw('SUM(gain_amount) as total_gain'),
            DB::raw(
                "
                ((SUM(CASE WHEN result = 'win' OR result = 'half' THEN 1 ELSE 0 END) / $totalMatch) * 100) as accurate
                "
            )
        )
            ->where('created_at', '>=', $periodStart->toDateTimeString())
            ->where('created_at', '<=', $periodEnd->toDateTimeString())
            ->groupBy('user_id')
            ->orderBy('accurate', 'desc')
            ->orderBy('user_id')
            ->limit(10)
            ->with('user')
            ->get();
    }

    /**
     * @param Carbon $start
     * @param Carbon $end
     * @return int
     */
    private function getTotalMatch(Carbon $start, Carbon $end): int
    {
        $startPredicTime = explode(':', config('settings.match_time_allow_to_start_predic', "10:00"));
        $endPredicTime = explode(':', config('settings.match_time_allow_to_end_predic', "09:59"));
        return GameFootballMatch::query()
            ->where('match_date', '>=', $start->setTime($startPredicTime[0], $startPredicTime[1])->toDateTimeString())
            ->where(
                'match_date',
                '<=',
                $end->addDay()->setTime($endPredicTime[0], $endPredicTime[1], 59)->toDateTimeString()
            )
            ->count();
    }

    /**
     * @return mixed
     */
    #[Computed(true, 3600, true)]
    public function sianAccurateMonth(): mixed
    {
        $periodStart = Carbon::now()->subDays(30)->startOfDay();
        $periodEnd = Carbon::now()->endOfDay();
        $totalMatch = $this->getTotalMatch(
            $periodStart->copy(),
            $periodEnd->copy()
        );
        return GameFootballPrediction::query()->select(
            'user_id',
            DB::raw('COUNT(id) as times'),
            DB::raw('SUM(gain_amount) as total_gain'),
            DB::raw(
                "
                ((SUM(CASE WHEN result = 'win' OR result = 'half' THEN 1 ELSE 0 END) / $totalMatch) * 100) as accurate
                "
            )
        )
            ->where('created_at', '>=', $periodStart->toDateTimeString())
            ->where('created_at', '<=', $periodEnd->toDateTimeString())
            ->groupBy('user_id')
            ->orderBy('accurate', 'desc')
            ->orderBy('user_id')
            ->limit(10)
            ->get();
    }

    /**
     * @return Factory|\Illuminate\Contracts\View\View|Application|View
     */
    public function render(): Factory|Application|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.index', [
            'mostPredicTeam' => $this->mostPredicTeam(),
            'sianAccurateWeek' => $this->sianAccurateWeek(),
            'sianAccurateMonth' => $this->sianAccurateMonth(),
        ]);
    }
}
