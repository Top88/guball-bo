<?php

namespace App\Livewire\Admin\Components;

use App\Domain\UserManagement\UserManagementService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagementTable extends Component
{
    use WithPagination;

    #[Url]
    #[Validate('integer|min:1')]
    public int $size = 10;

    #[Url]
    #[Validate('nullable|string|min:2')]
    public ?string $search = '';

    #[Computed]
    public function allUser()
    {
        /** @var UserManagementService $service */
        $service = app()->make(UserManagementService::class);

        return $service->getAllUsers($this->size, $this->getPage(), $this->search);
    }

    public function searchData()
    {
        $this->validate();
        $this->resetPage();
    }

    #[On('refreshUserManagementTable')]
    public function refreshUserManagementTable()
    {
        unset($this->allUser);
    }

    public function openUserModal(string $type = '', ?array $user = null)
    {
        $this->dispatch('open-modal', $type, $user);
    }

    public function deleteUser(string $userId)
    {
        $this->dispatch('confirmDeleteUser', $userId);
    }

    public function render()
    {
        return view('livewire.admin.components.user-management-table', [
            'users' => $this->allUser(),
        ]);
    }
}
