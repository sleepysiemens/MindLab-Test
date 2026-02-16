<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AbstractRequest;
class ChangePasswordRequest extends AbstractRequest
{
    public function rules(): array
    {
        return [
            'old_password'     => ['required', 'string', 'max:255'],
            'new_password'     => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'old_password.required'       => 'Текущий пароль не заполнен.',
            'new_password.required'       => 'Новый пароль не заполнен.',
            'new_password.max'            => 'Новый пароль должен содержать менее :max символов.',
        ];
    }
}
