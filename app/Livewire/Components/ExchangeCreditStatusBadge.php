<?php

namespace App\Livewire\Components;

use App\Domain\Credit\ExchangeCreditStatus;
use Livewire\Component;

class ExchangeCreditStatusBadge extends Component
{
    public string $status;
    public function mount(string $status)
    {
        $this->status = ExchangeCreditStatus::from($status)->value;
    }

    public function render()
    {
        return view('livewire.components.exchange-credit-status-badge');
    }
}
