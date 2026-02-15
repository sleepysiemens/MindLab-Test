<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractRequest;

class LoginRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email не заполнен.',
            'email.email' => 'Некорректный email.',
            'password.required' => 'Пароль не заполнен.',
        ];
    }
}
