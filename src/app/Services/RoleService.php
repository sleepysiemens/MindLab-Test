<?php

namespace App\Services;

use App\Interfaces\RoleServiceInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleService implements RoleServiceInterface
{
    public function paginate(int $onPageCount = 15, int $currPage = 1): LengthAwarePaginator
    {
        $params = [
            'on_page' => $onPageCount,
            'page'    => $currPage,
        ];

        return Cache::tags(['roles'])->rememberForever(
            'roles:' . md5(json_encode($params)),
            function () use ($onPageCount) {
                return Role::query()
                    ->select(['id', 'name', 'guard_name'])
                    ->orderBy('id')
                    ->paginate($onPageCount);
            }
        );
    }

    /**
     * @throws Throwable
     */
    public function getById(int $id): Role
    {
        return Cache::tags(['roles'])->rememberForever(
            "role:$id",
            function () use ($id) {
                $role = Role::query()
                    ->select(['id', 'name', 'guard_name'])
                    ->find($id);

                throw_if(
                    ! $role,
                    new ModelNotFoundException("Роль с ID = $id не найдена")
                );

                return $role;
            }
        );
    }

    public function createRole(array $data): Role
    {
        return Role::query()->create($data);
    }

    /**
     * @throws Throwable
     */
    public function updateRole(int $id, array $data): Role
    {
        $role = $this->getById($id);
        $role->update($data);

        return $role;
    }

    /**
     * @throws Throwable
     */
    public function deleteRole(int $id): void
    {
        $role = $this->getById($id);
        $role->delete();
    }
}
