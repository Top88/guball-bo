<?php

namespace App\Livewire\Admin\Components;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UserRoleSelect extends Component
{
    public string $userId;
    public ?string $role;
    public function mount(string $userId, ?string $role)
    {
        $this->userId = $userId;
        $this->role = $role;
    }

    public function changeRole(): void
    {
        User::find($this->userId)->syncRoles($this->role);
    }

    #[Computed]
    public function allRole()
    {
        return Role::query()->get();
    }

    public function render()
    {
        return view('livewire.admin.components.user-role-select',[
            'allRole' => $this->allRole()
        ]);
    }
}
