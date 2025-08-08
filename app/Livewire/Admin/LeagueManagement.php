<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin-app')]
class LeagueManagement extends Component
{
    public function render()
    {
        return view('livewire.admin.league-management')->title(__('website.general.manage_league'));
    }
}
