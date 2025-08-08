<?php

namespace App\Livewire\Admin\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Sidebar extends Component
{
    public $fullName;

    public $role;

    public function mount()
    {
        $this->fullName = Auth::user()->full_name;
        $this->role = ucwords(str_replace('-', ' ', Auth::user()->roles->first()->name));
    }

    public function render()
    {
        return view('livewire.admin.layouts.sidebar');
    }
}
