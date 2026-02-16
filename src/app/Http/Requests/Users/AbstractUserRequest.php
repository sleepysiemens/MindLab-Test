<?php

namespace App\Http\Requests\Users;

use App\Http\Requests\AbstractRequest;

class AbstractUserRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return $this->user->hasRole('admin');
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'name.max'       => 'Имя должно содержать менее :max символов.',
            'email.max'      => 'Email должен содержать менее :max символов.',
            'email.unique'   => 'Такой email уже используется.',
            'roles.*.exists' => 'Одна или несколько ролей не существует.'
        ];
    }
}
