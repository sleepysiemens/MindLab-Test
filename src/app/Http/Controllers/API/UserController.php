<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class UserController extends Controller
{
    public function __construct(private readonly UserService $userService) {}

    /** Вывод списка пользователей */
    public function index(): AnonymousResourceCollection
    {
        $users = $this->userService->paginate();

        return UserResource::collection($users);
    }

    /** Добавление пользователя */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());
        } catch (QueryException $e) {
            return $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return response()->json([
            'failed'  => false,
            'message' => 'Пользователь создан.',
            'data'    => $user,
        ]);
    }

    /** Информация о пользователе */
    public function show(int $id): UserResource
    {
        return new UserResource($this->userService->getById($id));
    }

    /** Обновление пользователя */
    public function update(int $id, UpdateUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());
        } catch (QueryException $e) {
            return $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return response()->json([
            'failed'  => false,
            'message' => 'Пользователь обновлен.',
            'data'    => $user,
        ]);
    }

    /** Удаление пользователя */
    public function delete(int $id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);
        }catch (QueryException $e) {
            return $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return response()->json([
            'failed'  => false,
            'message' => 'Пользователь удален.',
        ]);
    }

    public function deactivate(int $id): JsonResponse
    {
        try {
            $this->userService->updateUser($id, ['is_active' => false]);
        }catch (QueryException $e) {
            return $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return response()->json([
            'failed'  => false,
            'message' => 'Пользователь деактивирован.',
        ]);
    }

    public function errorHandle(string $message, string $error): JsonResponse
    {
        return response()->json([
            'failed'  => true,
            'message' => $message,
            'error'   => $error,
        ]);
    }
}
