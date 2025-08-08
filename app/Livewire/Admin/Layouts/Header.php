<?php

namespace App\Livewire\Admin\Layouts;

use App\Domain\Auth\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin-app')]
class Header extends Component
{
    public $fullName;

    public function mount()
    {
        $this->fullName = Auth::user()->full_name;
    }

    public function logout()
    {
        /** @var AuthService $authService */
        $authService = app()->make(AuthService::class);
        $authService->logout();

        return redirect()->route('index');
    }

    public function render()
    {
        return view('livewire.admin.layouts.header');
    }
}
