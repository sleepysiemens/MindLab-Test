<?php

namespace App\Services;


use App\Interfaces\AuthServiceInterface;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Throwable;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService implements AuthServiceInterface
{
    /**
     * @throws Throwable
     */
    public function login(array $data): string
    {
        $attemptRes = Auth::attempt($data);

        throw_if(
            ! $attemptRes,
            new AuthenticationException('Неверный email или пароль.')
        );

        if (! auth()->user()->is_active) {
            Auth::logout();

            throw new AccessDeniedException('Учетная запись деактивирована.');
        }

        return $attemptRes;
    }

    public function logout(): void
    {
        JWTAuth::parseToken()->invalidate();
    }

    public function refresh(): string
    {
        return Auth::refresh();
    }

    /**
     * @throws Throwable
     */
    public function changePassword(array $data): void
    {
        /** @var User $user */
        $user = auth()->user();

        throw_if(
            ! Hash::check($data['old_password'], $user->password),
            new ValidationException('Неверный пароль.'),
        );

        $user->update(['password' => $data['new_password']]);

        $this->logout();
    }
}
