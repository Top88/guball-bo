<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin-app')]
class RoleManagement extends Component
{

    public function render()
    {
        return view('livewire.admin.role-management');
    }
}
