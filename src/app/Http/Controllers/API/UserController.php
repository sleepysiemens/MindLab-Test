<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;

class UserController extends AbstractAPIController
{
    public function __construct(private readonly UserService $userService) {}

    /** Вывод списка пользователей */
    public function index(Request $request): AnonymousResourceCollection
    {
        $users = $this->userService->paginate(currPage: (int) $request->query('page'));

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

        return $this->getResponse(
            message: 'Пользователь создан.',
            data: new UserResource($user)
        );
    }

    /** Информация о пользователе */
    public function show(int $id): JsonResponse
    {
        return $this->getResponse(
            data: new UserResource($this->userService->getById($id))
        );
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

        return $this->getResponse(
            message: 'Пользователь обновлен.',
            data: new UserResource($user)
        );
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

        return $this->getResponse(
            message: 'Пользователь удален.',
        );
    }

    public function deactivate(int $id): JsonResponse
    {
        try {
            $this->userService->deactivateUser($id);
        }catch (QueryException $e) {
            return $this->errorHandle('Ошибка при работе с БД', $e->getMessage());
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка', $e->getMessage());
        }

        return $this->getResponse(
            message: 'Пользователь деактивирован.',
        );
    }
}
