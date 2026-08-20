<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        if (! Auth::attempt([
            'email' => $this->string('email'),
            'password' => $this->string('password'),
            'is_admin' => true,
        ], $this->boolean('remember'))) {
            $this->session()->flash('error', 'Invalid email or password.');

            throw ValidationException::withMessages([
                'email' => 'Invalid email or password.',
            ]);
        }
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        $this->session()->flash('error', $validator->errors()->first());

        parent::failedValidation($validator);
    }
}
