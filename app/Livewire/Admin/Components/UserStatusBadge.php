<?php

namespace App\Livewire\Admin\Components;

use App\Domain\UserManagement\UserManagementService;
use App\Infrastructure\User\Enums\UserStatus;
use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UserStatusBadge extends Component
{
    public User $user;

    public bool $isLink = false;

    public string $selected = '';

    public function mount(User $propUser, bool $propLink = false)
    {
        $this->user = $propUser;
        $this->isLink = $propLink;
        $this->selected = $propUser->status;
    }

    public function changeStatus(string $status)
    {
        $service = app()->make(UserManagementService::class);
        $service->changeUserStatus($this->user['id'], $status);
        $this->selected = $status;
        $this->dispatch('refreshUserManagementTable');
    }

    #[Computed]
    public function otherCases(): array
    {
        $allCases = UserStatus::cases();
        $filteredCases = array_filter(
            array_map(function (UserStatus $case) {
                // todo: just in case in the future
                if ($case == UserStatus::PENDING) {
                    return null;
                }

                return $case->value !== $this->selected ? $case->value : null;
            }, $allCases),
            fn ($case) => empty($case) ? false : true
        );

        return $filteredCases;
    }

    #[Computed]
    public function getDropdownColor()
    {
        return match (UserStatus::from($this->selected)) {
            UserStatus::ACTIVE => 'bg-success',
            UserStatus::INACTIVE => 'bg-secondary',
            UserStatus::BANNED => 'bg-danger',
            UserStatus::PENDING => 'bg-warning',
            default => 'bg-default',
        };
    }

    public function render()
    {
        return view('livewire.admin.components.user-status-badge');
    }
}
