<?php

namespace App\Livewire;

use App\Models\GameFootballMatch;
use App\Models\GameFootballPrediction;
use App\Models\User;
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
    private const ALLOWED_TYPES = ['single','step'];

    public User $user;

    // single | step (แยกสิทธิ์และดาต้า)
    public string $type = 'single';

    // ป้องกัน property ไม่ได้ประกาศแล้วถูกอ้างใน pointHistories()
    public ?string $searchMonth = null;

    private function canonical(string $t): string
    {
        $t = strtolower(trim($t));
        return in_array($t, self::ALLOWED_TYPES, true) ? $t : 'single';
    }

    public function mount(string $userId): void
    {
        // อ่าน type จาก query และ validate
        $q = request('type');
        $this->type = $this->canonical($q ?? 'single');

        // ตรวจสิทธิ์การดู (ต้องมี record และไม่หมดอายุ) + แยกตาม type
        $now = Carbon::now();
        $hasPermission = UserViewPrediction::query()
            ->where('asking_user_id', auth()->id())
            ->where('target_user_id', $userId)
            ->where('type', $this->type)   // ผูกตามประเภท
            ->where('expired_date', '>', $now)
            ->exists();

        if (! $hasPermission) {
            // ไม่มีสิทธิ์ -> กลับไปหน้าอันดับของประเภทเดิม
            $this->redirectRoute('prediction-rank', ['type' => $this->type], navigate: true);
            return;
        }

        $this->user = User::findOrFail($userId);
    }

    #[Computed]
    public function lastPredic()
    {
        // ใช้ setTimeFromTimeString เพราะ config เก็บเป็น 'HH:MM'
        $startAllowPredicTeam = Carbon::now()->setTimeFromTimeString(
            (string) config('settings.match_time_allow_to_start_predic', '10:00')
        );
        $endAllowPredicTeam = Carbon::now()->addDay()->setTimeFromTimeString(
            (string) config('settings.match_time_allow_to_end_predic', '09:59')
        );

        return GameFootballMatch::with(['league', 'homeTeam', 'awayTeam', 'prediction'])
            ->whereHas('prediction', function (Builder $query) {
                $query->where('user_id', $this->user->id)
                      ->where('type', $this->type); // กรองตาม type
            })
            ->where('match_date', '>', $startAllowPredicTeam->toDateTimeString())
            ->where('match_date', '<', $endAllowPredicTeam->toDateTimeString())
            ->orderBy('match_date', 'desc')
            ->get()
            ->groupBy('league_id');
    }

    #[Computed(true, 3600, true)]
    public function pointHistories(): Collection
    {
        return GameFootballPrediction::with(['team'])
            ->has('team')
            ->where('user_id', $this->user->id)
            ->where('type', $this->type) // กรองตาม type
            ->when(!empty($this->searchMonth ?? null), function ($query) {
                $date = Carbon::createFromFormat('Y-m', $this->searchMonth);
                $query->whereBetween('created_at', [
                    $date->copy()->startOfMonth(),
                    $date->copy()->endOfMonth(),
                ]);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(fn ($item) => $item->created_at->format('Y-m-d'));
    }

    // อันดับรวม (ตามตารางสถิติเดิม) — ไม่กรอง type (ตามลอจิกเดิม)
    #[Computed(true, 3600, true)]
    public function rank(): ?int
    {
        $row = DB::selectOne(
            "
            SELECT rankx FROM (
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
        );

        return $row->rankx ?? null;
    }

    public function render(): Application|Factory|View
    {
        return view('livewire.view-user-predicted', [
            'type' => $this->type,
        ]);
    }
}
