<?php

namespace App\Http\Requests\Roles;


class StoreRoleRequest extends AbstractRoleRequest
{
    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:255'],
            'guard_name' => ['string', 'max:255'],
        ];
    }
}
