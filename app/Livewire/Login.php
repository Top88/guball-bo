<?php

namespace App\Livewire;

use App\Domain\Auth\Services\AuthService;
use App\Livewire\Forms\LoginForm;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Container\BindingResolutionException;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;

    public function mount()
    {
        if (auth()->check()) {
            return redirect()->route('index');
        }
    }

    /**
     * @throws ValidationException
     * @throws BindingResolutionException
     */
    public function login(AuthService $authService): void
    {
        $this->validate();

        if ($authService->login($this->form->phone, $this->form->password)) {
            session()->regenerate();
            redirect()->route('index');
        } else {
            $this->addError('login_fail', 'เบอร์โทรศัพท์หรือรหัสผ่านไม่ถูกต้อง');
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
