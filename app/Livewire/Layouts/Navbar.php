<?php

namespace App\Livewire\Layouts;

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

    public function mount(): void
    {
        $this->updateUserData();
    }

    #[On('updated-user-data')]
    public function updateUserData(): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->coins_silver = (float) $user->coins_silver;
            $this->coins_gold   = (float) $user->coins_gold;
            $this->nickName     = (string) $user->nick_name;
        }
    }

    public function logout()
    {
        // หากคุณมี AuthService ภายในโดเมนเอง ก็สามารถใช้แทนบรรทัด Auth::logout() ได้:
        // app(\App\Domain\Auth\Services\AuthService::class)->logout();

        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        // Livewire v3: redirect พร้อม navigate จริง
        return $this->redirectRoute('login', navigate: true);
        // หรือกลับหน้าแรก: return $this->redirectRoute('index', navigate: true);
    }

    public function render()
    {
        return view('livewire.layouts.navbar');
    }
}
