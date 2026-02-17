<?php

namespace App\Http\Requests\Roles;

class UpdateRoleRequest extends AbstractRoleRequest
{
    public function rules(): array
    {
        return [
            'name' => ['string', 'max:255'],
        ];
    }
}
