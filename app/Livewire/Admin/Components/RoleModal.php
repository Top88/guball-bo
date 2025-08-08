<?php

namespace App\Livewire\Admin\Components;

use App\Domain\Roles\RoleForm;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleModal extends ModalComponent
{
    public RoleForm $form;
    public string $title = '';
    public ?int $roleId = null;
    public bool $editable = false;

    public function mount(string $type, ?int $roleId = null)
    {
        $this->roleId = $roleId;
        match ($type) {
          'add' => $this->addRole(),
          'edit' => $this->editRole(),
          default => throw new \Exception('Action not implemented'),
        };
    }

    public function addRole()
    {
        $this->title = 'เพิ่มตำแหน่ง';
        $this->form->name = '';
        $this->form->selectedPermissions = [];
        $this->editable = true;
    }

    public function saveRole()
    {
        DB::beginTransaction();
        if ($this->roleId) {
            $role = Role::find($this->roleId);
        } else {
            $role = new Role();
        }
        $role->name = $this->form->name;
        $role->display_name = $this->form->name;
        $role->description = '';
        $role->guard_name = 'web';
        $role->save();
        $role->syncPermissions($this->form->selectedPermissions);
        $this->clearForm();
        $this->dispatch('refreshRoleManagementTable');
        $this->forceClose()->closeModal();
        DB::commit();
    }

    public function editRole()
    {
        $this->title = 'แก้ไขตำแหน่ง';
        $role = Role::query()->where('id', $this->roleId)->first();
        $this->form->name = $role->name;
        $this->form->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->editable = !in_array($role->name, ['admin', 'super-admin', 'user']);
    }

    public function clearForm(): void
    {
        unset($this->form->name, $this->form->selectedPermissions);;
    }

    #[Computed]
    public function allPermissions()
    {
        return Permission::query()->orderBy('group')->get()->groupBy('group');
    }

    public function render()
    {
        return view('livewire.admin.components.role-modal', [
            'permissions' => $this->allPermissions(),
        ]);
    }
}
