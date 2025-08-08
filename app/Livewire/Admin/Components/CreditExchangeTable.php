<?php

namespace App\Livewire\Admin\Components;

use App\Models\ExchangeCredit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class CreditExchangeTable extends Component
{    use WithPagination;

    #[Url]
    #[Validate('integer|min:1')]
    public int $size = 10;

    #[Validate('nullable|string|min:2')]
    public ?string $search = '';

    public function searchData()
    {
        $this->validate();
        $this->resetPage();
    }

    #[Computed]
    public function exchangeList(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return ExchangeCredit::query()
            ->with(['user'])
            ->filter(['search' => $this->search ?? null])
            ->orderBy('created_at', 'desc')
            ->paginate( perPage: $this->size, page: $this->getPage());
    }

    #[On('refreshExchangeCreditManagementTable')]
    public function refreshExchangeCreditManagementTable(): void
    {
        unset($this->exchangeList);
    }

    public function render()
    {
        return view('livewire.admin.components.credit-exchange-table', [
            'exchange_list' => $this->exchangeList()
        ]);
    }
}
