<?php

namespace App\Livewire\Forms;

use App\Enums\UserRole;
use Illuminate\Validation\Rule;
use Livewire\Form;

class UserForm extends Form
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = 'user';

    public ?int $userId = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => [$this->userId ? 'nullable' : 'required', 'string', 'confirmed'],
            'role' => ['required', Rule::enum(UserRole::class)],
        ];
    }
}
