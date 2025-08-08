<?php

namespace App\Livewire;

use App\Domain\SweetAlert\NormalAlert;
use App\Exceptions\NotEnoughPointForExchangeCoin;
use App\Helper\ThaiDate;
use App\Jobs\ExchangeCreditJob;
use App\Jobs\ExchangePointJob;
use App\Models\GameFootballPrediction;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class PointHistory extends Component
{
    #[Url('m', history: true)]
    public ?string $searchMonth = null;

    #[Locked]
    public ?float $exchangeRate = null;

    public ?float $coinExpect = 1;

    #[Computed]
    public float $userGoldCoins = 0;

    #[Computed]
    public float $userSilverCoins = 0;

    public function mount()
    {
        $this->exchangeRate = config('settings.cost_for_point_to_coin');
        $this->userSilverCoins = Auth::user()->coins_silver;
        $this->userGoldCoins = Auth::user()->coins_gold;
    }

    #[Computed(true, 60, true)]
    public function pointHistories(): Collection
    {
        return GameFootballPrediction::with(['team'])
            ->has('team')
            ->where('user_id', Auth::user()->id)
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

    #[Computed(true, seconds: true)]
    public function monthList()
    {
        return GameFootballPrediction::with(['team'])
            ->has('team')
            ->where('user_id', Auth::user()->id)
            ->get()
            ->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            })->keys()->mapWithKeys(function ($item) {
                $date = Carbon::createFromFormat('Y-m-d', $item);

                return [$date->format('Y-m') => ThaiDate::toShortMonthYear($date->toDateTimeString())];
            });
    }

    #[Computed]
    public function exchangeCreateLeft()
    {
        return round(auth()->user()->points / $this->exchangeRate, 0, PHP_ROUND_HALF_DOWN);
    }

    public function updatedSearchMonth()
    {
        unset($this->pointHistories);
    }

    public function refreshUserData()
    {
        $user = Auth::user()->refresh();
        $this->userGoldCoins = $user->coins_gold;
        $this->userSilverCoins = $user->coins_silver;
    }

    /**
     * @return void
     */
    public function submitExchangeCredit(): void
    {
        try {
            if (auth()->user()?->coins_gold < config('settings.exchange_credit_cost', 2000)) {
                $this->dispatch('sweet-alert', (new NormalAlert('มีเหรียญทองไม่เพียงพอ', 'warning'))->toArray());
                return;
            }
            ExchangeCreditJob::dispatchSync(Auth::id(), Auth::id());
            $this->refreshUserData();
            $this->dispatch('sweet-alert', (new NormalAlert(
                'แลกเครดิตสำเร็จ',
                'success',
            ))->toArray());
        } catch (\Exception $e) {
            $this->dispatch('sweet-alert', (new NormalAlert(
                'แลกเครดิตไม่สำเร็จ'. $e->getMessage(),
                'error',
            ))->toArray());
        }
    }

    public function render()
    {
        return view('livewire.point-history');
    }
}
