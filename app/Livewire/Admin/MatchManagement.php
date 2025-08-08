<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin-app')]
class MatchManagement extends Component
{
    public function render()
    {
        return view('livewire.admin.match-management');
    }
}
