<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Football\RateType;
use Livewire\Attributes\On;
use Livewire\Component;

class RateTypeSelect extends Component
{
    public ?string $selectRateTypeName = null;

    public ?string $modalId = null;

    public string $fieldName = '';

    public function mount(string $fieldName, ?string $modalId = '')
    {
        $this->modalId = $modalId;
        $this->fieldName = $fieldName;
    }

    public function boot()
    {
        $this->dispatch('init-select', ['isModal' => $this->modalId ? true : false, 'modalId' => $this->modalId]);
    }

    public function updatedSelectRateTypeName()
    {
        $this->dispatch('set-rate-type-from-select', $this->selectRateTypeName);
    }

    #[On('set-default-field-{fieldName}')]
    public function setField(?string $name)
    {
        $this->selectRateTypeName = $name;
        $this->dispatch('trigger-select-change-'.$this->fieldName, ['name' => $name]);
    }

    #[On('reset-field-{fieldName}')]
    public function resetField()
    {
        $this->reset(['selectRateTypeName']);
    }

    public function render()
    {
        return view('livewire.admin.components.rate-type-select', [
            'rateTypes' => array_column(RateType::cases(), 'value'),
        ]);
    }
}
