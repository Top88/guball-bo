<?php

namespace App\Domain\Roles;

use Livewire\Attributes\Validate;
use Livewire\Form;

class RoleForm extends Form
{
    #[Validate('required|string|max:255')]
    public string $name = '';
    #[Validate('required|array|min:1')]
    public array $selectedPermissions = [];
}