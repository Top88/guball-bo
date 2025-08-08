<?php

namespace App\Livewire;

use App\Domain\SweetAlert\NormalAlert;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ChangePassword extends Component
{
    #[Validate(
        ['required'],
    )]
    public string $oldPassword = "";
    #[Validate(
        [
            'required',
            'same:confirmPassword',
        ]
    )]
    public string $newPassword = "";
    #[Validate(
        ['required'],
    )]
    public string $confirmPassword = "";

    public function submit(): void
    {
        $this->validate();

        $user = User::query()->find(auth()->id());
        if (Crypt::decryptString($user->password) !== $this->oldPassword) {
            $this->addError('oldPassword', 'รหัสผ่านเดิมไม่ถูกต้อง');
            return;
        }
        User::query()->where('id', auth()->id())->update(['password' => Crypt::encryptString($this->newPassword)]);
        $this->dispatch('sweet-alert', (new NormalAlert(
            'เปลี่ยนรหัสผ่านสำเร็จ',
            'success',
        ))->toArray());
        $this->resetField();
    }

    public function resetField()
    {
        $this->reset(['oldPassword', 'newPassword', 'confirmPassword']);
        $this->resetValidation();
    }

    public function render(): Application|Factory|\Illuminate\Contracts\View\View|View
    {
        return view('livewire.change-password');
    }
}
