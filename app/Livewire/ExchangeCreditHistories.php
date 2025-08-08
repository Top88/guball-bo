<?php

namespace App\Livewire;

use App\Models\ExchangeCredit;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ExchangeCreditHistories extends Component
{
    #[Computed]
    public function getHistories()
    {
        return ExchangeCredit::query()
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.exchange-credit-histories', [
            'histories' => $this->getHistories(),
        ]);
    }
}
