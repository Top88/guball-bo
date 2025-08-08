<?php

namespace App\Livewire\Admin;

use App\Domain\SweetAlert\ConfirmationAlert;
use App\Domain\SweetAlert\NormalAlert;
use App\Helper\ThaiDate;
use App\Jobs\ResetAllSilverCoin;
use App\Models\Setting as Settings;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.admin-app')]
class Setting extends Component
{
    public string $search = '';
    public array $weekDayThai = ThaiDate::WEEK_DAY;

    #[Computed]
    public function settings()
    {
        return Settings::where('editable', true)->paginate();
    }

    #[On('re-setting-table')]
    public function reSetting()
    {
        unset($this->settings);
    }

    public function resetAllSilverCoin()
    {
        $this->dispatch('sweet-alert-confirmation', (new ConfirmationAlert(
            'ต้องการล้างเหรียญเงินทั้งระบบหรือไม่?',
            'เมื่อยืนยันแล้วจะไม่สามารถคืนค่าได้',
            'warning',
            'ยืนยันการล้างเหรียญ',
            'confirm-reset-silver-coin',
        ))->toArray());
    }

    #[On('confirm-reset-silver-coin')]
    public function confirmedResetSilverCoin()
    {
        ResetAllSilverCoin::dispatch(Auth::id());
        $this->dispatch('sweet-alert', (new NormalAlert('รีเซตเหรียญเงินสำเร็จ', 'success'))->toArray());
    }

    public function render()
    {
        return view('livewire.admin.setting');
    }
}
