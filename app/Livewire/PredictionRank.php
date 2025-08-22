<?php

namespace App\Livewire;

use App\Domain\Coin\SilverCoinHistroryAction;
use App\Domain\SweetAlert\NormalAlert;
use App\Models\User;
use App\Models\UserFootballPredictionStatistics;
use App\Models\UserSilverCoinTransactionLog;
use App\Models\UserViewPrediction;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PredictionRank extends Component
{
    use WithPagination, WithoutUrlPagination;

    private const ALLOWED_TYPES = ['single', 'step'];

    public ?string $targetViewPredictionId = null;
    public float $costForPrediction = 0;
    public string $type = 'single';

    // ช่องค้นหา (ค้นหาเฉพาะ nick_name)
    public ?string $searchWeek  = null;
    public ?string $searchMonth = null;

    private function canonical(string $t): string
    {
        $t = strtolower(trim($t));
        return in_array($t, self::ALLOWED_TYPES, true) ? $t : 'single';
    }

    public function mount(string $type = 'single'): void
    {
        $q = request('type');
        $this->type = $this->canonical($q ?: $type);
    }

    /** Filters */
    private function applyTypeFilter($query)
    {
        return $query->where('type', $this->type);
    }

    private function clampTier(int $order): int
    {
        return max(1, min(3, $order));
    }

    private function resolveViewCost(int $order): float
    {
        if ($order >= 4) return 0.0;

        $tier = $this->clampTier($order);
        $prefix = $this->type === 'step'
            ? 'cost_for_view_prediction_step_'
            : 'cost_for_view_prediction_single_';

        $defaults = [1 => 30, 2 => 20, 3 => 10];
        $key = $prefix . $tier;
        $val = config('settings.' . $key);

        if ($val === null) {
            $legacy = config('settings.cost_for_view_prediction_number_' . $tier);
            return (float) ($legacy ?? $defaults[$tier]);
        }
        return (float) $val;
    }

    private function goToViewPrediction(string $userId): void
    {
        $url = route('view-prediction', ['userId' => $userId]) . '?type=' . $this->type;
        $this->redirect($url, navigate: true);
    }

    /** ===== Search helpers (nick_name เท่านั้น) ===== */
    private function escapeLike(string $v): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $v);
    }

    private function applyUserSearch($query, ?string $term)
    {
        $term = trim((string) $term);
        if ($term === '') return $query;
        $safe = $this->escapeLike($term);
        return $query->where('users.nick_name', 'like', "%{$safe}%");
    }

    // reset page เมื่อพิมพ์
    public function updatedSearchWeek(): void  { $this->resetPage('rank-week-page'); }
    public function updatedSearchMonth(): void { $this->resetPage('rank-month-page'); }

    // ปุ่มค้นหา
    public function applySearchWeek(): void  { $this->resetPage('rank-week-page'); }
    public function applySearchMonth(): void { $this->resetPage('rank-month-page'); }

    /** อันดับรวม (ยังคงเดิมตามสถิติรวม) */
    #[Computed]
    public function getTopRank(): LengthAwarePaginator
    {
        return UserFootballPredictionStatistics::query()
            ->with([
                'user' => function ($query) {
                    $query->withCount('predicToday')
                        ->with([
                            'gameFootBallPrediction' => function (HasMany $query) {
                                $this->applyTypeFilter($query)
                                    ->whereNotNull('result')
                                    ->orderBy('created_at', 'desc')
                                    ->orderBy('id', 'desc')
                                    ->limit(10);
                            },
                            'targetViewPrediction' => function (HasMany $query) {
                                $query->where('asking_user_id', auth()->id())
                                      ->where('type', $this->type)
                                      ->where('expired_date', '>', Carbon::now());
                            },
                        ]);
                }
            ])
            ->whereHas('user.gameFootBallPrediction', function ($query) {
                $this->applyTypeFilter($query)->whereNotNull('result');
            })
            ->orderByDesc('points')
            ->orderByDesc('win')
            ->orderByDesc('win_half')
            ->orderByDesc('draw')
            ->orderBy('lose')
            ->paginate(10, pageName: 'rank-all-page');
    }

    /** ===== รายสัปดาห์ ===== */
    #[Computed]
    public function getTopRankByWeek(): LengthAwarePaginator
    {
        $start = now()->copy()->startOfWeek(Carbon::MONDAY);
        $end   = $start->copy()->addWeek(); // exclusive
        $includeBonus = $this->type === 'step';

        $base = function ($q) use ($start, $end) {
            $this->applyTypeFilter($q)
                ->whereNotNull('result')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<',  $end);
            return $q;
        };
        $baseAll = function ($q) use ($start, $end) {
            $this->applyTypeFilter($q)
                ->where('created_at', '>=', $start)
                ->where('created_at', '<',  $end);
            return $q;
        };

        // โบนัสสเต็ปจาก log ภายในช่วงสัปดาห์
        $bonusSub = DB::table('user_gold_coin_transaction_log')
            ->selectRaw('COALESCE(SUM(`change`),0)')
            ->whereColumn('user_id', 'users.id')
            ->where('action', 'bonus_step3_fullwin')
            ->whereBetween('created_at', [$start, $end]);

        // === Query หลัก (ใส่ค้นหาได้)
        $result = User::query()
            ->select('users.*')
            ->selectSub($bonusSub, 'bonus_sum') // alias: bonus_sum
            ->when($this->searchWeek, fn($q) => $this->applyUserSearch($q, $this->searchWeek))
            ->withCount('predicToday')
            ->with([
                'targetViewPrediction' => function (HasMany $query) {
                    $query->where('asking_user_id', auth()->id())
                          ->where('type', $this->type)
                          ->where('expired_date', '>', Carbon::now());
                },
                'gameFootBallPrediction' => function (HasMany $query) use ($base) {
                    $base($query)->orderByDesc('created_at')->orderByDesc('id');
                },
            ])
            ->whereHas('gameFootBallPrediction', $base)

            ->withSum(['gameFootBallPrediction as points_sum' => $base], 'gain_amount')
            ->withCount(['gameFootBallPrediction as win'      => fn($q) => $base($q)->where('result', 'win')])
            ->withCount(['gameFootBallPrediction as win_half' => fn($q) => $base($q)->whereIn('result', ['win_half', 'winhalf'])])
            ->withCount(['gameFootBallPrediction as draw'     => fn($q) => $base($q)->where('result', 'draw')])
            ->withCount(['gameFootBallPrediction as lose'     => fn($q) => $base($q)->where('result', 'lose')])

            ->withCount(['gameFootBallPrediction as played'   => $base])
            ->withCount(['gameFootBallPrediction as attempts' => $baseAll])

            ->when(
                $includeBonus,
                fn($q) => $q->orderByDesc(DB::raw('COALESCE(points_sum,0) + COALESCE(bonus_sum,0)')),
                fn($q) => $q->orderByDesc('points_sum')
            )
            ->orderByDesc('played')
            ->orderByDesc('win')
            ->orderByDesc('win_half')
            ->orderByDesc('draw')
            ->orderBy('lose')
            ->orderBy('users.id')
            ->paginate(10, pageName: 'rank-week-page');

        // === ทำ rank map จาก "ทั้งตาราง" (ไม่ใช้คำค้นหา) เพื่อได้อันดับจริง
        $rankBase = User::query()
            ->select('users.id')
            ->selectSub($bonusSub, 'bonus_sum')
            ->withSum(['gameFootBallPrediction as points_sum' => $base], 'gain_amount')
            ->withCount(['gameFootBallPrediction as win'      => fn($q) => $base($q)->where('result', 'win')])
            ->withCount(['gameFootBallPrediction as win_half' => fn($q) => $base($q)->whereIn('result', ['win_half', 'winhalf'])])
            ->withCount(['gameFootBallPrediction as draw'     => fn($q) => $base($q)->where('result', 'draw')])
            ->withCount(['gameFootBallPrediction as lose'     => fn($q) => $base($q)->where('result', 'lose')])
            ->withCount(['gameFootBallPrediction as played'   => $base])
            ->whereHas('gameFootBallPrediction', $base);

        if ($includeBonus) {
            $rankBase->orderByDesc(DB::raw('COALESCE(points_sum,0) + COALESCE(bonus_sum,0)'));
        } else {
            $rankBase->orderByDesc('points_sum');
        }

        $rankIds = $rankBase
            ->orderByDesc('played')
            ->orderByDesc('win')
            ->orderByDesc('win_half')
            ->orderByDesc('draw')
            ->orderBy('lose')
            ->orderBy('users.id')
            ->pluck('users.id')
            ->all();

        $rankMap = [];
        foreach ($rankIds as $idx => $uid) {
            $rankMap[$uid] = $idx + 1; // อันดับจริง
        }

        // เติม points (ฐาน+โบนัสถ้า step) และอันดับจริงเข้า collection
        $result->getCollection()->transform(function ($user) use ($rankMap, $includeBonus) {
            $base   = (float) ($user->points_sum ?? 0);
            $bonus  = $includeBonus ? (float) ($user->bonus_sum ?? 0) : 0.0;
            $user->points    = $base + $bonus;
            $user->rank_real = $rankMap[$user->id] ?? null;
            return $user;
        });

        return $result;
    }

    /** ===== รายเดือน ===== */
    #[Computed]
    public function getTopRankByMonth(): LengthAwarePaginator
    {
        $start = now()->startOfMonth();
        $end   = $start->copy()->addMonth()->startOfMonth(); // exclusive
        $includeBonus = $this->type === 'step';

        $base = function ($q) use ($start, $end) {
            $this->applyTypeFilter($q)
                ->whereNotNull('result')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<',  $end);
            return $q;
        };
        $baseAll = function ($q) use ($start, $end) {
            $this->applyTypeFilter($q)
                ->where('created_at', '>=', $start)
                ->where('created_at', '<',  $end);
            return $q;
        };

        $bonusSub = DB::table('user_gold_coin_transaction_log')
            ->selectRaw('COALESCE(SUM(`change`),0)')
            ->whereColumn('user_id', 'users.id')
            ->where('action', 'bonus_step3_fullwin')
            ->whereBetween('created_at', [$start, $end]);

        // ชุดแสดงผล (ใส่ค้นหา)
        $result = User::query()
            ->select('users.*')
            ->selectSub($bonusSub, 'bonus_sum')
            ->when($this->searchMonth, fn($q) => $this->applyUserSearch($q, $this->searchMonth))
            ->withCount('predicToday')
            ->with([
                'targetViewPrediction' => function (HasMany $query) {
                    $query->where('asking_user_id', auth()->id())
                          ->where('type', $this->type)
                          ->where('expired_date', '>', Carbon::now());
                },
                'gameFootBallPrediction' => function (HasMany $query) use ($base) {
                    $base($query)->orderByDesc('created_at')->orderByDesc('id');
                },
            ])
            ->whereHas('gameFootBallPrediction', $base)

            ->withSum(['gameFootBallPrediction as points_sum' => $base], 'gain_amount')
            ->withCount(['gameFootBallPrediction as win'      => fn($q) => $base($q)->where('result', 'win')])
            ->withCount(['gameFootBallPrediction as win_half' => fn($q) => $base($q)->whereIn('result', ['win_half', 'winhalf'])])
            ->withCount(['gameFootBallPrediction as draw'     => fn($q) => $base($q)->where('result', 'draw')])
            ->withCount(['gameFootBallPrediction as lose'     => fn($q) => $base($q)->where('result', 'lose')])

            ->withCount(['gameFootBallPrediction as played'   => $base])
            ->withCount(['gameFootBallPrediction as attempts' => $baseAll])

            ->when(
                $includeBonus,
                fn($q) => $q->orderByDesc(DB::raw('COALESCE(points_sum,0) + COALESCE(bonus_sum,0)')),
                fn($q) => $q->orderByDesc('points_sum')
            )
            ->orderByDesc('played')
            ->orderByDesc('win')
            ->orderByDesc('win_half')
            ->orderByDesc('draw')
            ->orderBy('lose')
            ->orderBy('users.id')
            ->paginate(10, pageName: 'rank-month-page');

        // สร้าง rank map ของทั้งเดือน (ไม่ใส่คำค้นหา)
        $rankBase = User::query()
            ->select('users.id')
            ->selectSub($bonusSub, 'bonus_sum')
            ->withSum(['gameFootBallPrediction as points_sum' => $base], 'gain_amount')
            ->withCount(['gameFootBallPrediction as win'      => fn($q) => $base($q)->where('result', 'win')])
            ->withCount(['gameFootBallPrediction as win_half' => fn($q) => $base($q)->whereIn('result', ['win_half', 'winhalf'])])
            ->withCount(['gameFootBallPrediction as draw'     => fn($q) => $base($q)->where('result', 'draw')])
            ->withCount(['gameFootBallPrediction as lose'     => fn($q) => $base($q)->where('result', 'lose')])
            ->withCount(['gameFootBallPrediction as played'   => $base])
            ->whereHas('gameFootBallPrediction', $base);

        if ($includeBonus) {
            $rankBase->orderByDesc(DB::raw('COALESCE(points_sum,0) + COALESCE(bonus_sum,0)'));
        } else {
            $rankBase->orderByDesc('points_sum');
        }

        $rankIds = $rankBase
            ->orderByDesc('played')
            ->orderByDesc('win')
            ->orderByDesc('win_half')
            ->orderByDesc('draw')
            ->orderBy('lose')
            ->orderBy('users.id')
            ->pluck('users.id')
            ->all();

        $rankMap = [];
        foreach ($rankIds as $idx => $uid) {
            $rankMap[$uid] = $idx + 1;
        }

        $result->getCollection()->transform(function ($user) use ($rankMap, $includeBonus) {
            $base   = (float) ($user->points_sum ?? 0);
            $bonus  = $includeBonus ? (float) ($user->bonus_sum ?? 0) : 0.0;
            $user->points    = $base + $bonus;
            $user->rank_real = $rankMap[$user->id] ?? null;
            return $user;
        });

        return $result;
    }

    /** === ดูผลล่าสุด / หักเหรียญ === */

    public function selectViewPrediction(string $targetUserId, int $order): void
    {
        $this->targetViewPredictionId = $targetUserId;
        $this->costForPrediction = $this->resolveViewCost($order);
        // รองรับทั้ง v2/v3
        if (method_exists($this, 'dispatch')) {
            $this->dispatch('open-view-prediction-modal');
        } else {
            $this->dispatchBrowserEvent('open-view-prediction-modal');
        }
    }

    public function viewPredictionResult(string $targetUserId)
    {
        $this->goToViewPrediction($targetUserId);
    }

    public function submitViewPrediction()
    {
        $this->validate([
            'targetViewPredictionId' => 'required|exists:users,id',
        ]);

        if ($this->costForPrediction <= 0) {
            UserViewPrediction::updateOrCreate(
                [
                    'asking_user_id' => auth()->id(),
                    'target_user_id' => $this->targetViewPredictionId,
                    'type'           => $this->type,
                ],
                ['expired_date'   => now()->endOfDay()->toDateTimeString()]
            );

            $id = $this->targetViewPredictionId;
            $this->resetFields();
            $this->goToViewPrediction($id);
            return;
        }

        if (auth()->user()->coins_silver < $this->costForPrediction) {
            if (method_exists($this, 'dispatch')) {
                $this->dispatch('sweet-alert', (new NormalAlert('เหรียญของท่านไม่เพียงพอ', 'warning'))->toArray());
            } else {
                $this->dispatchBrowserEvent('sweet-alert', (new NormalAlert('เหรียญของท่านไม่เพียงพอ', 'warning'))->toArray());
            }
            return;
        }

        DB::beginTransaction();
        try {
            /** @var User $user */
            $user = User::findOrFail(auth()->id());
            $current = $user->coins_silver;

            $user->decrement('coins_silver', $this->costForPrediction);
            $user->refresh();

            UserSilverCoinTransactionLog::create([
                'user_id'    => $user->id,
                'action'     => SilverCoinHistroryAction::VIEW_FOOTBALL_PREDICTION->value,
                'current'    => $current,
                'change'     => 0 - $this->costForPrediction,
                'balance'    => $user->coins_silver,
                'updated_by' => $user->id,
            ]);

            UserViewPrediction::updateOrCreate(
                [
                    'asking_user_id' => $user->id,
                    'target_user_id' => $this->targetViewPredictionId,
                    'type'           => $this->type,
                ],
                ['expired_date'   => now()->endOfDay()->toDateTimeString()]
            );

            DB::commit();

            $id = $this->targetViewPredictionId;
            $this->resetFields();
            $this->goToViewPrediction($id);
        } catch (Exception $e) {
            DB::rollBack();
            if (method_exists($this, 'dispatch')) {
                $this->dispatch('sweet-alert', (new NormalAlert('ไม่สามารถดูผลล่าสุดได้', 'error'))->toArray());
            } else {
                $this->dispatchBrowserEvent('sweet-alert', (new NormalAlert('ไม่สามารถดูผลล่าสุดได้', 'error'))->toArray());
            }
        }
    }

    public function resetFields(): void
    {
        $this->targetViewPredictionId = null;
        $this->costForPrediction = 0;
        unset($this->getTopRank, $this->getTopRankByWeek, $this->getTopRankByMonth);
    }

    public function render()
    {
        return view('livewire.prediction-rank', [
            'top_rank_list'          => $this->getTopRank(),
            'top_rank_list_by_week'  => $this->getTopRankByWeek(),
            'top_rank_list_by_month' => $this->getTopRankByMonth(),
            'type'                   => $this->type,
        ]);
    }
}
