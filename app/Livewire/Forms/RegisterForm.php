<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
    #[Validate('required|string|max:255')]
    public $nickName = '';

    #[Validate('required|string|max:255')]
    public $fullName = '';

    #[Validate(['required', 'regex:/^(\+?[\d\s]{10,15})$/'])]
    public $phone = '';

    #[Validate('required|string|min:8|max:255')]
    public $password = '';

    public function clearForm()
    {
        $this->nickName = '';
        $this->fullName = '';
        $this->phone = '';
        $this->password = '';
    }
}
