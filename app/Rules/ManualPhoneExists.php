<?php

namespace App\Rules;

use App\Domain\UserManagement\UserManagementService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ManualPhoneExists implements ValidationRule
{
    public function __construct(private mixed $existsPhone) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($value !== $this->existsPhone) {
            $userManagementService = app()->make(UserManagementService::class);
            if ($userManagementService->getUserByPhone($value)) {
                $fail(__('validation.exists', ['attribute' => __('validation.attributes.'.$attribute)]));
            }
        }
    }
}
