<?php

namespace App\Http\Requests\Users;
class UpdateUserRequest extends AbstractUserRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['string', 'max:255'],
            'email'    => ['email', 'unique:users,email', 'max:255'],
            'password' => ['string'],
            'roles'    => ['sometimes', 'array'],
            'roles.*'  => ['string', 'exists:roles,name'],
        ];
    }
}
