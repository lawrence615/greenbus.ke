<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage users');
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'password_option' => ['required', 'in:auto,manual'],
            'send_invite' => ['nullable', 'boolean'],
            'require_password_change' => ['nullable', 'boolean'],
        ];

        // Password is required only when manual option is selected
        if ($this->input('password_option') === 'manual') {
            $rules['password'] = ['required', Password::defaults()];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'A user with this email address already exists.',
            'role.exists' => 'The selected role is invalid.',
            'password.required' => 'Password is required when creating a manual password.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'send_invite' => $this->boolean('send_invite'),
            'require_password_change' => $this->boolean('require_password_change'),
        ]);
    }
}
