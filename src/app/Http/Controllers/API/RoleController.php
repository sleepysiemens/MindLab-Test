<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\StoreRoleRequest;
use App\Http\Requests\Roles\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Services\RoleService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class RoleController extends Controller
{
    public function __construct(private readonly RoleService $roleService) {}

    /** Вывод списка ролей */
    public function index(): AnonymousResourceCollection
    {
        $roles = $this->roleService->paginate();

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

        return response()->json([
            'failed'  => false,
            'message' => 'Роль создана.',
            'data'    => new RoleResource($role),
        ]);
    }

    /** Информация о роли */
    public function show(int $id): RoleResource
    {
        return new RoleResource($this->roleService->getById($id));
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

        return response()->json([
            'failed'  => false,
            'message' => 'Роль обновлена',
            'data'    => new RoleResource($role),
        ]);
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

        return response()->json([
            'failed'  => false,
            'message' => 'Роль удалена.',
            ]);
    }
}
