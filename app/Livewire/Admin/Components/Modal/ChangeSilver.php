<?php

namespace App\Livewire\Admin\Components\Modal;

use App\Domain\Coin\SilverCoinHistroryAction;
use App\Domain\SweetAlert\NormalAlert;
use App\Domain\UserManagement\ChangeSideEnum;
use App\Events\SilverCoinChangeHistoryEvent;
use App\Exceptions\UserNotFoundException;
use App\Infrastructure\Coins\SilverCoinHistoryFactory;
use App\Jobs\CreateUserSilverCoinLogJob;
use App\Models\User;
use Auth;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;

class ChangeSilver extends ModalComponent
{
    public float $amount = 0.0;

    public string $userId;
    
    public string $changeSide;

    public function mount($userId)
    {
        $this->userId = $userId;
        $this->changeSide = ChangeSideEnum::INCREMENT->value;
    }

    #[Computed]
    public function user()
    {
        return User::where('id', $this->userId)->first();
    }


    public function save()
    {
        $this->validate([
            'amount' => 'required|numeric|min:0.1'
        ]);
        $user = User::where('id', $this->userId)->first();
        if (null === $user) {
            throw new UserNotFoundException();
        }
        $change = 0;
        $logSide = null;
        $current = $user->coins_silver;
        if ($this->changeSide === ChangeSideEnum::INCREMENT->value) {
            $change = $this->amount;
            $user->increment('coins_silver', $this->amount);
            $logSide = SilverCoinHistroryAction::MANUAL_INCREMENT;
        } elseif ($this->changeSide === ChangeSideEnum::DECREMENT->value) {
            $change = $this->amount * -1;
            $user->decrement('coins_silver', $this->amount);
            $logSide = SilverCoinHistroryAction::MANUAL_DECREASE;
        }
        $user->refresh();
        $prelog = SilverCoinHistoryFactory::willCreate(
            $user->id, 
            $logSide,
            $current,
            $change,
            $user->coins_silver,
            Auth::user()->id,
        );
        
        CreateUserSilverCoinLogJob::dispatch($prelog);
        $this->dispatch('refreshUserManagementTable');
        $this->dispatch('closeModal');
        $this->dispatch('sweet-alert', (new NormalAlert(
            ($this->changeSide === ChangeSideEnum::INCREMENT->value ? 'เพิ่ม': 'ลด').'เหรียญเงินสำเร็จ',
            'success'
        ))->toArray());
    }

    public function increment()
    {
        $this->changeSide = ChangeSideEnum::INCREMENT->value;
    }

    public function decrement()
    {
        $this->changeSide = ChangeSideEnum::DECREMENT->value;
    }

    public function render()
    {
        return view('livewire.admin.components.modal.change-silver');
    }
}
