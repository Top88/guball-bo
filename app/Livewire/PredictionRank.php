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

    public ?string $targetViewPredictionId = null;
    public float $costForPrediction = 0;

    // ✅ รับชนิดเกมจาก route: single | step (ค่าเริ่มต้น single)
    public string $type = 'single';

    public function mount(string $type = 'single'): void
    {
        $q = request('type'); // รองรับ ?type=...
        $type = strtolower(trim($q ?: $type));
        $this->type = in_array($type, ['single', 'step']) ? $type : 'single';
    }

    // ===== Helper: ใส่เงื่อนไขกรองชนิดเกมลงใน query =====
    private function applyTypeFilter($query)
    {
        // ในตารางมีค่า single/step ชัดเจนจากสกรีนช็อต
        return $query->where('type', $this->type);
    }

    // ===== อันดับรวม (คงลอจิกเดิม แต่กรองเฉพาะ type ที่เลือก) =====
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
                                      ->where('expired_date', '>', Carbon::now());
                            },
                        ]);
                }
            ])
            ->whereHas('user.gameFootBallPrediction', function ($query) {
                $this->applyTypeFilter($query)->whereNotNull('result');
            })
            // หมายเหตุ: การเรียงตามสถิติรวมยังเหมือนเดิม (ถ้าตารางสถิติรวมทุกประเภทไว้ ก็จะเรียงตามรวม)
            ->orderByDesc('points')
            ->orderByDesc('win')
            ->orderByDesc('win_half')
            ->orderByDesc('draw')
            ->orderBy('lose')
            ->paginate(10, pageName: 'rank-all-page');
    }

    // ===== อันดับรายสัปดาห์ =====
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
            ->withWhereHas('gameFootBallPrediction', function ($query) use ($starDateOfWeek, $endDateOfWeek) {
                $this->applyTypeFilter($query)
                    ->whereNotNull('result')
                    ->whereBetween('created_at', [
                        $starDateOfWeek->toDateTimeString(),
                        $endDateOfWeek->toDateTimeString()
                    ])
                    ->orderBy('created_at', 'desc')
                    ->orderBy('id', 'desc')
                    ->limit(10);
            })
            ->addSelect(['gain_sum' => $subQuery])
            ->orderByDesc('gain_sum')
            ->paginate(10, pageName: 'rank-week-page');

        // สรุปชนะ/เสมอ/แพ้/คะแนน — กรองเฉพาะ type นี้อีกรอบเพื่อความชัวร์
        $result->getCollection()->transform(function ($user) {
            $win = $winHalf = $draw = $lose = $points = 0;

            $preds = $user->gameFootBallPrediction
                ? $user->gameFootBallPrediction->where('type', $this->type)
                : collect();

            /** @var GameFootballPrediction $prediction */
            foreach ($preds as $prediction) {
                switch ($prediction->result) {
                    case 'win':      $win++; break;
                    case 'winhalf':
                    case 'win_half': $winHalf++; break;
                    case 'draw':     $draw++; break;
                    case 'lose':     $lose++; break;
                }
                $points += (float) $prediction->gain_amount;
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

    // ===== อันดับรายเดือน =====
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

            $preds = $user->gameFootBallPrediction
                ? $user->gameFootBallPrediction->where('type', $this->type)
                : collect();

            /** @var GameFootballPrediction $prediction */
            foreach ($preds as $prediction) {
                switch ($prediction->result) {
                    case 'win':      $win++; break;
                    case 'winhalf':
                    case 'win_half': $winHalf++; break;
                    case 'draw':     $draw++; break;
                    case 'lose':     $lose++; break;
                }
                $points += (float) $prediction->gain_amount;
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

    // ===== ด้านล่างคงเดิม (ดูผลล่าสุด/หักเหรียญ) =====
    public function selectViewPrediction(string $targetUserId, int $order): void
    {
        $this->targetViewPredictionId = $targetUserId;
        $this->costForPrediction = (float) config('settings.cost_for_view_prediction_number_'.$order);
        $this->openViewPredictionModal();
    }

    public function viewPredictionResult(string $targetUserId)
    {
        $this->redirectRoute('view-prediction', ['userId' => $targetUserId]);
    }

    public function openViewPredictionModal()
    {
        $this->dispatch('open-view-prediction-modal');
    }

    public function submitViewPrediction()
    {
        $this->validate([
            'targetViewPredictionId' => 'required|exists:users,id',
        ]);

        if (auth()->user()->coins_silver < $this->costForPrediction) {
            $this->dispatch('sweet-alert', (new NormalAlert(
                'เหรียญของท่านไม่เพียงพอ',
                'warning',
            ))->toArray());
            return;
        }

        DB::beginTransaction();
        /** @var User $user */
        $user = User::find(auth()->user()->id);
        $currentCoin = $user->coins_silver;

        try {
            $user->decrement('coins_silver', $this->costForPrediction);
            $user->refresh();

            UserSilverCoinTransactionLog::create([
                'user_id'   => $user->id,
                'action'    => SilverCoinHistroryAction::VIEW_FOOTBALL_PREDICTION->value,
                'current'   => $currentCoin,
                'change'    => 0 - $this->costForPrediction,
                'balance'   => $user->coins_silver,
                'updated_by'=> $user->id,
            ]);

            UserViewPrediction::create([
                'asking_user_id' => auth()->user()->id,
                'target_user_id' => $this->targetViewPredictionId,
                'expired_date'   => Carbon::now()->endOfDay()->toDateTimeString(),
            ]);

            DB::commit();
            $this->redirectRoute('view-prediction', ['userId' => $this->targetViewPredictionId]);
            $this->resetFields();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('sweet-alert', (new NormalAlert(
                'ไม่สามารถดูผลล่าสุดได้',
                'error',
            ))->toArray());
        }
    }

    public function resetFields(): void
    {
        $this->targetViewPredictionId = null;
        unset($this->getTopRank, $this->getTopRankByWeek);
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