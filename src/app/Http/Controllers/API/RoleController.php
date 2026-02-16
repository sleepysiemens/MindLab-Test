<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class RoleController extends AbstractAPIController
{
    public function __construct(private readonly RoleService $roleService) {}

    /** Вывод списка ролей */
    public function index(Request $request): AnonymousResourceCollection
    {
        $roles = $this->roleService->paginate(currPage: (int) $request->query('page'));

        return RoleResource::collection($roles);
    }

    /** Создание роли */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());
        } catch (QueryException $e) {
            return $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return $this->getResponse(
            message: 'Роль создана.',
            data: new RoleResource($role)
        );
    }

    /** Информация о роли */
    public function show(int $id): JsonResponse
    {
        return $this->getResponse(
            data: new RoleResource($this->roleService->getById($id))
        );
    }

    /** Обновление роли */
    public function update(int $id, UpdateRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->updateRole($id, $request->validated());
        } catch (QueryException $e) {
            return $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return $this->getResponse(
            message: 'Роль обновлена.',
            data: new RoleResource($role)
        );
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->roleService->deleteRole($id);
        } catch (QueryException $e) {
            $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return $this->getResponse(
            message: 'Роль удалена.');
    }
}
