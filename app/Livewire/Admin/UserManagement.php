<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin-app')]
class UserManagement extends Component
{
    public function render()
    {
        return view('livewire.admin.user-management')->title(__('website.general.manage_user'));
    }
}
