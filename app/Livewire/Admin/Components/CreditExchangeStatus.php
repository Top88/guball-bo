<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Credit\ExchangeCreditStatus;
use App\Models\ExchangeCredit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

use function PHPUnit\Framework\matches;

class CreditExchangeStatus extends Component
{
    public int $exchangeId;
    public string $selected;
    public function mount(int $exchangeId, string $status)
    {
        $this->exchangeId = $exchangeId;
        $this->selected = $status;
    }

    public function changeStatus(string $selected): void
    {
        $this->selected = $selected;
        ExchangeCredit::query()->where('id', $this->exchangeId)->update(['exchange_status' => ExchangeCreditStatus::from($this->selected)]);
    }

    public function colorStatus(): string
    {
        return match(ExchangeCreditStatus::from($this->selected)) {
            ExchangeCreditStatus::PENDING => 'btn-warning',
            ExchangeCreditStatus::COMPLETED => 'btn-success',
            ExchangeCreditStatus::APPROVED => 'btn-primary',
            ExchangeCreditStatus::REJECTED => 'btn-danger',
        };
    }

    public function getList()
    {
        return ExchangeCreditStatus::cases();
    }

    /**
     * @throws \Exception
     */
    public function render()
    {
        return view('livewire.admin.components.credit-exchange-status', [
            'status_list' => $this->getList(),
            'color_status' => $this->colorStatus(),
        ]);
    }
}
