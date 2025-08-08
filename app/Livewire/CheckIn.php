<?php

namespace App\Livewire;

use App\Domain\User\UserCheckInService;
use App\Exceptions\UserNotFoundException;
use App\Models\CheckInSetting;
use App\Models\UserCheckIn;
use Auth;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Application;
use LaravelIdea\Helper\App\Models\_IH_CheckInSetting_C;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Throwable;

#[Layout('components.layouts.app')]
class CheckIn extends Component
{
    /**
     * @var Carbon|null
     */
    public ?Carbon $startCheckInDate = null;

    /**
     * @var Carbon|null
     */
    public ?Carbon $endCheckInDate = null;

    /**
     * @var int
     */
    public int $checkinPeriod = 0;

    /**
     * @var array
     */
    public array $periodList = [];

    /**
     * @var array
     */
    public array $usercheckedIn = [];

    /**
     * @var string
     */
    public string $dialogHeaderText = '';

    /**
     * @var bool
     */
    public bool $dialogSuccess = false;

    /**
     * @var float|int
     */
    public float $userCoinsSilver = 0;

    /**
     * @return void
     */
    public function mount()
    {
        $this->userCoinsSilver = Auth::user()->coins_silver;
        $date = Carbon::createFromFormat('Y-m-d H:i:s', config('settings.start_check_in_date'));
        $this->startCheckInDate = $date;
        $this->endCheckInDate = $date->clone()->addDays((int) config('settings.check_in_period'))->endOfDay();
        $this->checkinPeriod = (int) config('settings.check_in_period');
        $this->setUsercheckedIn();
        $this->setPeriodList();
    }

    /**
     * @return void
     */
    public function setPeriodList()
    {
        $startDate = $this->startCheckInDate->clone();
        for ($i = 1; $i <= $this->checkinPeriod; $i++) {
            $this->periodList[] = $startDate->toDateString();
            $startDate->addDay();
        }
    }

    /**
     * @return CheckInSetting[]|Collection|_IH_CheckInSetting_C
     */
    #[Computed]
    public function setting()
    {
        return CheckInSetting::all();
    }

    /**
     * @return void
     * @throws UserNotFoundException
     * @throws ErrorException
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function checkIn()
    {
        $check = UserCheckIn::where('user_id', Auth::id())
            ->whereDate('checked_date', Carbon::now()->toDateString())->first();
        if ($check) {
            $this->dialogHeaderText = 'วันนี้คุณเช็คอินไปแล้ว';
            $this->dialogSuccess = false;
            $this->dispatch('check-in-fail');

            return;
        }
        /**
         * @var UserCheckInService $service
         */
        $dayCheckIn = array_search(Carbon::now()->toDateString(), $this->periodList) ?? null;
        if ($dayCheckIn === null) {
            $this->faileCheckIn();

            return;
        }

        $service = app()->make(UserCheckInService::class);
        $reward = $service->checkIn(Auth::id(), (int) $dayCheckIn + 1);
        if (empty($reward)) {
            $this->faileCheckIn();
            return;
        }
        $this->dialogHeaderText = "ยินดีด้วยคุณได้รับ $reward";
        $this->dialogSuccess = true;
        $this->dispatch('check-in-success');
        $this->setUsercheckedIn();
        $this->userCoinsSilver = Auth::user()->fresh()->coins_silver;
        $this->dispatch('updated-user-data');
    }

    /**
     * @return void
     */
    public function faileCheckIn()
    {
        $this->dialogHeaderText = 'เช็คอินไม่สำเร็จ';
        $this->dialogSuccess = false;
        $this->dispatch('check-in-fail');
    }

    /**
     * @return void
     */
    public function setUsercheckedIn()
    {
        $this->usercheckedIn = UserCheckIn::query()->where('user_id', Auth::id())->whereBetween('checked_date', [$this->startCheckInDate, $this->endCheckInDate])->get()->pluck('checked_date')->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })->toArray();
    }

    /**
     * @return Factory|View|Application
     */
    public function render()
    {
        return view('livewire.check-in')->extends('layouts.app');
    }
}
