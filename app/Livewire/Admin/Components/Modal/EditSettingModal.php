<?php

namespace App\Livewire\Admin\Components\Modal;

use App\Helper\ThaiDate;
use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use LivewireUI\Modal\ModalComponent;

class EditSettingModal extends ModalComponent
{
    public string $settingKey = '';

    public string $settingValue = '';

    public string $type = '';

    public array $weekDayThai = ThaiDate::WEEK_DAY;

    public function mount(string $settingKey, string $value, string $type)
    {
        $this->settingKey = $settingKey;
        $this->settingValue = $value;
        $this->type = $type;
    }

    public function save()
    {
        Setting::where('key', $this->settingKey)->update([
            'value' => $this->settingValue,
        ]);
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        $this->dispatch('re-setting-table');
        $this->forceClose()->closeModal();
    }

    public function render()
    {
        return view('livewire.admin.components.modal.edit-setting-modal');
    }
}
