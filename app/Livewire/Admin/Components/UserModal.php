<?php

namespace App\Livewire\Admin\Components;

use App\Domain\SweetAlert\ConfirmationAlert;
use App\Domain\SweetAlert\NormalAlert;
use App\Domain\UserManagement\UserManagementService;
use Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;

class UserModal extends Component
{
    public string $userId = '';

    public string $phone = '';

    public string $password = '';

    public string $fullName = '';

    public string $nickName = '';

    public string|float $coinsSilver = 0;

    public string|float $coinsGold = 0;

    public string $modalType = '';

    public string $modalTypeLabel = '';

    public ?array $user;

    protected $rules = [
        'name' => 'required|min:3',
    ];

    public function mount()
    {
        $this->modalTypeLabel = __('website.general.add');
    }

    #[On('open-modal')]
    public function openModal(string $type = 'add', ?array $user = null)
    {
        $this->modalType = $type;
        $this->user = $user;
        $this->modalTypeLabel = __('website.general.'.$type);
        // Reset form fields and validation
        $this->resetValidation();
        $this->resetField();
        // Emit event to open the modal
        if ($type === 'edit' && $user !== null) {
            $this->setDataToModal($user);
        }
        $this->dispatch('open-user-modal');
    }

    public function setDataToModal(array $user)
    {
        $this->userId = $user['id'] ?? '';
        $this->phone = $user['phone'] ?? '';
        $this->fullName = $user['full_name'] ?? '';
        $this->nickName = $user['nick_name'] ?? '';
        $this->coinsSilver = number_format($user['coins_silver'], 0, 0);
        $this->coinsGold = number_format($user['coins_gold'], 0, 0);
    }

    public function submit()
    {
        if ($this->modalType === 'add') {
            $this->createUser();
        } elseif ($this->modalType === 'edit') {
            $this->updateUser();
        } else {
            $this->dispatch('sweet-alert', [
                'type' => 'error',
                'title' => __('website.alert.error'),
                'text' => __('website.alert.error_message'),
            ]);
        }
        $this->dispatch('hide-user-modal');
        $this->dispatch('refreshUserManagementTable');
    }

    public function createUser()
    {
        $this->coinsSilver = preg_replace('/[^\d.-]/', '', $this->coinsSilver);
        $this->coinsGold = preg_replace('/[^\d.-]/', '', $this->coinsGold);
        $this->validate([
            'phone' => 'required|numeric|unique:users',
            'password' => 'required|string|min:6',
            'fullName' => 'required|string',
            'nickName' => 'required|string',
            'coinsSilver' => 'required|numeric|min:0',
            'coinsGold' => 'required|numeric|min:0',
        ]);
        /** @var UserManagementService $userManagementService */
        $userManagementService = app()->make(UserManagementService::class);
        $userManagementService->createUser(
            $this->phone,
            $this->password,
            $this->fullName,
            $this->nickName,
            $this->coinsSilver,
            $this->coinsGold,
            Auth::id()
        );

        $this->dispatch('sweet-alert', [
            'type' => 'success',
            'title' => __('website.alert.create_user_success'),
        ]);
    }

    public function updateUser()
    {
        $this->coinsSilver = preg_replace('/[^\d.-]/', '', $this->coinsSilver);
        $this->coinsGold = preg_replace('/[^\d.-]/', '', $this->coinsGold);
        /** @var UserManagementService $userManagementService */
        $userManagementService = app()->make(UserManagementService::class);
        $this->validate([
            'phone' => [
                'required',
                'numeric',
                Rule::unique('users', 'phone')->ignore($this->userId),
            ],
            'password' => 'nullable|string|min:6',
            'fullName' => 'required|string',
            'nickName' => 'required|string',
            'coinsSilver' => 'required|numeric|min:0',
            'coinsGold' => 'required|numeric|min:0',
        ]);
        $userManagementService->updateUser(
            $this->userId,
            $this->phone,
            $this->password,
            $this->fullName,
            $this->nickName,
            $this->coinsSilver,
            $this->coinsGold,
            Auth::id(),
        );

        $this->dispatch('sweet-alert', (new NormalAlert(__('website.alert.edit_user_success'), 'success'))->toArray());
    }

    #[On('confirmDeleteUser')]
    public function deleteUser(string $userId)
    {
        $this->dispatch('sweet-alert-confirmation', (new ConfirmationAlert(
            __('website.alert.delete_user_title'),
            __('website.alert.delete_user_message'),
            'warning',
            __('website.button.delete_button_text'),
            'confirmedDeleteUserEvent',
            ['user_id' => $userId]
        ))->toArray());
    }

    #[On('confirmedDeleteUserEvent')]
    public function confirmedDeleteUser(array $data)
    {
        /** @var UserManagementService $userManagementService */
        $userManagementService = app()->make(UserManagementService::class);
        $userManagementService->deleteUser($data['user_id']);
        $this->dispatch('sweet-alert', (new NormalAlert(
            __('website.alert.delete_user_confirmed_title'),
            'success',
        ))->toArray());
        $this->dispatch('refreshUserManagementTable');
    }

    #[On('close-modal')]
    public function handleCloseModal()
    {
        $this->resetField();
        $this->resetValidation();
    }

    public function resetField()
    {
        $this->reset(['phone', 'password', 'fullName', 'nickName', 'coinsSilver', 'coinsGold']);
    }

    public function render()
    {
        return view('livewire.admin.components.user-modal');
    }
}
