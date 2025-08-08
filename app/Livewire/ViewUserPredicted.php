<?php

namespace App\Livewire;

use App\Models\GameFootballMatch;
use App\Models\GameFootballPrediction;
use App\Models\User;
use App\Models\UserFootballPredictionStatistics;
use App\Models\UserViewPrediction;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ViewUserPredicted extends Component
{
    public User $user;

    public function mount(string $userId)
    {
        $now = Carbon::now();
        $check = UserViewPrediction::where('asking_user_id', auth()->user()->id)
            ->where('target_user_id', $userId)
            ->where('expired_date', '>', $now)
            ->first();
        if ($check === null) {
            $this->redirectRoute('prediction-rank');

            return;
        }
        $this->user = User::find($userId);
    }

    #[Computed]
    public function lastPredic()
    {
        $startAllowPredicTeam = Carbon::now()->setTimeFrom( config('settings.match_time_allow_to_start_predic', '10:00'));
        $endAllowPredicTeam = Carbon::now()->addDay()->setTimeFrom( config('settings.match_time_allow_to_end_predic', '09:59'));

        return GameFootballMatch::with(['league', 'homeTeam', 'awayTeam', 'prediction'])
            ->whereHas('prediction', callback: function (Builder $query) {
                $query->where('user_id', $this->user->id);
                $query->orderBy('created_at', 'desc');
            })
            ->where('match_date', '>', $startAllowPredicTeam->toDateTimeString())
            ->where('match_date', '<', $endAllowPredicTeam->toDateTimeString())
            ->get()
            ->groupBy('league_id');
    }

    #[Computed(true, 3600, true)]
    public function pointHistories(): Collection
    {
        return GameFootballPrediction::with(['team'])
            ->has('team')
            ->where('user_id', $this->user->id)
            ->where(function (Builder $query) {
                if (! empty($this->searchMonth)) {
                    $date = Carbon::createFromFormat('Y-m', $this->searchMonth);
                    $query->whereBetween('created_at', [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()]);
                }
            })
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

    }

    #[Computed(true, 3600, true)]

    public function rank(): ?int
    {
        return DB::select(
            "
            SELECT rankx
            FROM (
                SELECT user_id,
                       DENSE_RANK() OVER (
                           ORDER BY
                               points DESC,
                               win DESC,
                               win_half DESC,
                               draw DESC,
                               lose ASC
                           ) AS rankx
                FROM user_football_prediction_statistics
            ) ranks_subquery
            WHERE user_id = ?;
        ",
            [$this->user->id]
        )[0]?->rankx ?? null;
    }

    public function render(): Application|Factory|View
    {
        return view('livewire.view-user-predicted');
    }
}
