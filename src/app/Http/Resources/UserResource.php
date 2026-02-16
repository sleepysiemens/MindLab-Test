<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property bool $is_active
 * @property mixed roles
 * @property Carbon|null $created_at
 */
class UserResource extends JsonResource
{
    /**@return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'is_active'  => $this->is_active,
            'roles'      => $this->roles->pluck('name'),
            'created_at' => $this->created_at,
        ];
    }
}
