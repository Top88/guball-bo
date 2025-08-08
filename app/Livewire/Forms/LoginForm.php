<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    #[Validate(['required', 'string', 'max:255'])]
    public string $phone = '';

    #[Validate(['required', 'string', 'max:255'])]
    public string $password = '';
}
