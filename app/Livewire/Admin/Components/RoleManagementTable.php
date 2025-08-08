<?php

namespace App\Livewire\Admin\Components;

use App\Domain\SweetAlert\ConfirmationAlert;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class RoleManagementTable extends Component
{
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function roles()
    {
        return Role::query()
            ->with('permissions')
            ->when(isset($this->search) && $this->search !== '', fn ($query) =>
                $query->where('name', 'like', '%' . $this->search . '%')
            )
            ->paginate();
    }

    #[On('refreshRoleManagementTable')]
    public function refreshRoleManagementTable(): void
    {
        unset($this->roles);
    }

    public function searchData()
    {
        $this->resetPage();
    }

    public function deleteRole(int $roleId)
    {
        $alert = new ConfirmationAlert(
            'ลบตำแหน่ง',
            'ยืนยันหรือไม่?',
            'warning',
            'ยืนยัน',
            'confirm-delete-role',
            ['roleId' => $roleId]
        );
        $this->dispatch('sweet-alert-confirmation', $alert->toArray());
    }

    #[On('confirm-delete-role')]
    public function confirmDelete(array $data): void
    {
        Role::find($data['roleId'])->delete();
        $this->dispatch('refreshRoleManagementTable');
    }

    public function render()
    {
        return view('livewire.admin.components.role-management-table', [
            'roles' => $this->roles(),
        ]);
    }
}
