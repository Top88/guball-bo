<?php

namespace App\Livewire\Layouts;

use App\Domain\Auth\Services\AuthService;
use App\Infrastructure\Traits\LivewireComponentTrait;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    use LivewireComponentTrait;

    public float $coins_silver = 0;

    public float $coins_gold = 0;
    public string $nickName = '';

    public function mount()
    {
        $this->updateUserData();
    }

    #[On('updated-user-data')]
    public function updateUserData()
    {
        if (Auth::check()) {
            $this->coins_silver = Auth::user()->coins_silver;
            $this->coins_gold = Auth::user()->coins_gold;
            $this->nickName = Auth::user()->nick_name;
        }
    }

    public function logout(AuthService $authService): void
    {
        $authService->logout();
        self::redirectRoute('login');
    }

    public function render()
    {
        return view('livewire.layouts.navbar');
    }
}
