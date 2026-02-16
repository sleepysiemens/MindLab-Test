<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Throwable;

class AuthController extends AbstractAPIController
{
    public function __construct(protected readonly AuthService $authService){}

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->login($request->validated());

        } catch (AuthenticationException $e) {
            return $this->errorHandle(message: $e->getMessage(), code: 401);
        } catch (AccessDeniedException $e) {
            return $this->errorHandle(message: $e->getMessage(), code: 403);
        } catch (Throwable $e) {
            return $this->errorHandle(message: 'Произошла ошибка.', error: $e->getMessage());
        }

        return $this->getResponse(
            message: 'Успешная авторизация.',
            data: [
                'token'      => $token,
                'expires_in' => Auth::factory()->getTTL() * 60
            ]
        );
    }

    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->getResponse(message: 'Вы успешно вышли из системы.',);
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = $this->authService->refresh();
        } catch (Throwable $e) {
            return $this->errorHandle('Произошла ошибка.', $e->getMessage());
        }

        return $this->getResponse(
            message: 'Токен обновлен.',
            data: [
                'token'      => $token,
                'expires_in' => Auth::factory()->getTTL() * 60
            ]
        );
    }

    /** Просмотр информации о пользователе */
    public function show(): UserResource
    {
        return new UserResource(auth()->user());
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $this->authService->changePassword($request->validated());
        } catch (Throwable $e) {
            return $this->errorHandle($e->getMessage(), code: 422);
        }

        return $this->getResponse(message: 'Пароль успешно сброшен. Выполните вход с новым паролем.',);
    }
}
