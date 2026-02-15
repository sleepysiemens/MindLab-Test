<?php

namespace App\Services;

use App\Interfaces\RoleServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

class RoleService implements RoleServiceInterface
{
    public function paginate(int $onPageCount = 15): LengthAwarePaginator
    {
        return Role::query()
            ->select(['name', 'guard_name'])
            ->orderBy('id')
            ->paginate($onPageCount);
    }

    public function getById(int $id): Role
    {
        $role = Role::query()
            ->select(['name', 'guard_name'])
            ->find($id);

        if (! $role) {
            throw new ModelNotFoundException("Роль с ID = $id не найдена");
        }

        return $role;
    }

    public function createRole(array $data): Role
    {
        return Role::query()->create($data);
    }

    public function updateRole(int $id, array $data): Role
    {
        $role = $this->getById($id);
        $role->update($data);

        return $role;
    }

    public function deleteRole(int $id): void
    {
        $role = $this->getById($id);
        $role->delete();
    }
}
