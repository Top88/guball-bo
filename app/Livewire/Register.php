<?php

namespace App\Livewire;

use App\Domain\Auth\Services\AuthService;
use App\Infrastructure\Traits\LivewireComponentTrait;
use App\Infrastructure\Traits\LivewireExceptionTrait;
use App\Livewire\Forms\RegisterForm;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('สมัครสมาชิก')]
class Register extends Component
{
    use LivewireComponentTrait;
    use LivewireExceptionTrait;

    public RegisterForm $form;

    public function mount()
    {
        self::IsAuth();
    }

    /**
     * @throws ValidationException
     * @throws Exception
     * @throws BindingResolutionException
     */
    public function register(AuthService $authService): void
    {
        $this->validate();
        $result = $authService->register(
            $this->form->nickName,
            $this->form->fullName,
            $this->form->phone,
            $this->form->password
        );
        if ($result) {
            $this->form->clearForm();
            $this->dispatch('sweet-alert', ['type' => 'success', 'title' => __('website.alert.register_success')]);
            $this->dispatch('redirect-after-delay', ['path' => 'login']);
        } else {
            self::redirectBack();
        }
    }

    public function render()
    {
        return view('livewire.register');
    }
}
