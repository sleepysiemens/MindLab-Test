<?php

namespace App\Http\Requests\Roles;

use App\Http\Requests\AbstractRequest;

class AbstractRoleRequest extends AbstractRequest
{
    public function authorize(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [];
    }

    public function messages(): array
    {
        return [
            'name.max'       => 'Название должно содержать менее :max символов.',
            'name.required'  => 'Название должно быть заполнено.',
            'guard_name.max' => 'Guard name должен содержать менее :max символов.',
        ];
    }
}
