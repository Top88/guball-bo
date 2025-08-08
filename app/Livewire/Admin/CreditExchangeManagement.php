<?php

namespace App\Livewire\Admin;

use App\Models\ExchangeCredit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin-app')]
class CreditExchangeManagement extends Component
{

    public function render()
    {
        return view('livewire.admin.credit-exchange-management');
    }
}
