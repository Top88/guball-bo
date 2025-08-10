<?php

namespace App\Livewire;

use App\Domain\Coin\SilverCoinHistroryAction;
use App\Domain\SweetAlert\NormalAlert;
use App\Models\GameFootballPrediction;
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

    private const ALLOWED_TYPES = ['single','step'];

    public ?string $targetViewPredictionId = null;
    public float $costForPrediction = 0;

    // single | step
    public string $type = 'single';

    /** บังคับให้ type เป็นค่าใน ALLOWED_TYPES เท่านั้น */
    private function canonical(string $t): string
    {
        $t = strtolower(trim($t));
        return in_array($t, self::ALLOWED_TYPES, true) ? $t : 'single';
    }

    public function mount(string $type = 'single'): void
    {
        $q = request('type'); // รองรับ ?type=...
        $this->type = $this->canonical($q ?: $type);
    }

    /** Helpers */
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

    // helper redirect — บังคับติด query ?type=
    private function goToViewPrediction(string $userId): void
    {
        $url = route('view-prediction', ['userId' => $userId]) . '?type=' . $this->type;
        $this->redirect($url, navigate: true);
    }

    /** อันดับรวม */
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
                            // ✅ สิทธิ์ดู: ผูกตามประเภทด้วย
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

    /** รายสัปดาห์ */
    #[Computed]
    public function getTopRankByWeek(): LengthAwarePaginator
    {
        $startWeekDay   = (int) config('settings.show_rank_week_day', 0);
        $endDateOfWeek  = Carbon::now()->next($startWeekDay);
        $starDateOfWeek = $endDateOfWeek->copy()->startOfDay()->subDays(7);

        $subQuery = GameFootballPrediction::query()
            ->selectRaw('SUM(gain_amount)')
            ->whereColumn('user_id', 'users.id');
        $this->applyTypeFilter($subQuery)
            ->whereNotNull('result')
            ->whereBetween('created_at', [
                $starDateOfWeek->toDateTimeString(),
                $endDateOfWeek->toDateTimeString()
            ]);

        $result = User::query()
            ->withCount('predicToday')
            ->with([
                // ✅ สิทธิ์ดู: ผูกตามประเภทด้วย
                'targetViewPrediction' => function (HasMany $query) {
                    $query->where('asking_user_id', auth()->id())
                          ->where('type', $this->type)
                          ->where('expired_date', '>', Carbon::now());
                },
                'gameFootBallPrediction' => function (HasMany $query) use ($starDateOfWeek, $endDateOfWeek) {
                    $this->applyTypeFilter($query)
                        ->whereNotNull('result')
                        ->whereBetween('created_at', [
                            $starDateOfWeek->toDateTimeString(),
                            $endDateOfWeek->toDateTimeString()
                        ])
                        ->orderBy('created_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->limit(10);
                },
            ])
            ->withWhereHas('gameFootBallPrediction', function ($query) use ($starDateOfWeek, $endDateOfWeek) {
                $this->applyTypeFilter($query)
                    ->whereNotNull('result')
                    ->whereBetween('created_at', [
                        $starDateOfWeek->toDateTimeString(),
                        $endDateOfWeek->toDateTimeString()
                    ]);
            })
            ->addSelect(['gain_sum' => $subQuery])
            ->orderByDesc('gain_sum')
            ->paginate(10, pageName: 'rank-week-page');

        $result->getCollection()->transform(function ($user) {
            $win = $winHalf = $draw = $lose = $points = 0;
            $preds = ($user->gameFootBallPrediction ?? collect())->where('type', $this->type);

            foreach ($preds as $p) {
                $points += (float) $p->gain_amount;
                switch ($p->result) {
                    case 'win': $win++; break;
                    case 'winhalf':
                    case 'win_half': $winHalf++; break;
                    case 'draw': $draw++; break;
                    case 'lose': $lose++; break;
                }
            }

            $user->win      = $win;
            $user->win_half = $winHalf;
            $user->draw     = $draw;
            $user->lose     = $lose;
            $user->points   = $points;
            return $user;
        });

        return $result;
    }

    /** รายเดือน */
    #[Computed]
    public function getTopRankByMonth(): LengthAwarePaginator
    {
        $starDate = Carbon::now()->startOfMonth();
        $endDate  = $starDate->copy()->endOfMonth();

        $subQuery = GameFootballPrediction::query()
            ->selectRaw('SUM(gain_amount)')
            ->whereColumn('user_id', 'users.id');
        $this->applyTypeFilter($subQuery)
            ->whereNotNull('result')
            ->whereBetween('created_at', [
                $starDate->toDateTimeString(),
                $endDate->toDateTimeString()
            ]);

        $result = User::query()
            ->withWhereHas('gameFootBallPrediction', function ($query) use ($starDate, $endDate) {
                $this->applyTypeFilter($query)
                    ->whereNotNull('result')
                    ->whereBetween('created_at', [
                        $starDate->toDateTimeString(),
                        $endDate->toDateTimeString()
                    ])
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc')
                    ->limit(10);
            })
            ->addSelect(['gain_sum' => $subQuery])
            ->orderByDesc('gain_sum')
            ->paginate(10, pageName: 'rank-month-page');

        $result->getCollection()->transform(function ($user) {
            $win = $winHalf = $draw = $lose = $points = 0;
            $preds = ($user->gameFootBallPrediction ?? collect())->where('type', $this->type);

            foreach ($preds as $p) {
                $points += (float) $p->gain_amount;
                switch ($p->result) {
                    case 'win': $win++; break;
                    case 'winhalf':
                    case 'win_half': $winHalf++; break;
                    case 'draw': $draw++; break;
                    case 'lose': $lose++; break;
                }
            }

            $user->win      = $win;
            $user->win_half = $winHalf;
            $user->draw     = $draw;
            $user->lose     = $lose;
            $user->points   = $points;
            return $user;
        });

        return $result;
    }

    /** === ดูผลล่าสุด / หักเหรียญ === */

    public function selectViewPrediction(string $targetUserId, int $order): void
    {
        $this->targetViewPredictionId = $targetUserId;
        $this->costForPrediction = $this->resolveViewCost($order);
        $this->dispatch('open-view-prediction-modal');
    }

    // กรณีมีสิทธิ์อยู่แล้ว (จาก targetViewPrediction) → ไปดูได้เลย
    public function viewPredictionResult(string $targetUserId)
    {
        $this->goToViewPrediction($targetUserId);
    }

    public function submitViewPrediction()
    {
        $this->validate([
            'targetViewPredictionId' => 'required|exists:users,id',
        ]);

        // อันดับ >= 4 → ฟรี แต่ต้องสร้างสิทธิ์ไว้ให้ผ่านหน้าเช็คสิทธิ์
        if ($this->costForPrediction <= 0) {
            UserViewPrediction::updateOrCreate(
                [
                    'asking_user_id' => auth()->id(),
                    'target_user_id' => $this->targetViewPredictionId,
                    'type'           => $this->type, // ✅ ผูกตามประเภท
                ],
                [
                    'expired_date'   => now()->endOfDay()->toDateTimeString(),
                ]
            );

            $id = $this->targetViewPredictionId;
            $this->resetFields();
            $this->goToViewPrediction($id);
            return;
        }

        if (auth()->user()->coins_silver < $this->costForPrediction) {
            $this->dispatch('sweet-alert', (new NormalAlert('เหรียญของท่านไม่เพียงพอ', 'warning'))->toArray());
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

            // บันทึกสิทธิ์พร้อม type
            UserViewPrediction::updateOrCreate(
                [
                    'asking_user_id' => $user->id,
                    'target_user_id' => $this->targetViewPredictionId,
                    'type'           => $this->type, // ✅ ผูกตามประเภท
                ],
                [
                    'expired_date'   => now()->endOfDay()->toDateTimeString(),
                ]
            );

            DB::commit();

            $id = $this->targetViewPredictionId;
            $this->resetFields();
            $this->goToViewPrediction($id);
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('sweet-alert', (new NormalAlert('ไม่สามารถดูผลล่าสุดได้', 'error'))->toArray());
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
