<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function paginate(int $onPageCount = 15): LengthAwarePaginator
    {
        return User::query()
            ->select(['id', 'email', 'name', 'is_active', 'created_at'])
            ->with('roles')
            ->orderBy('id')
            ->paginate($onPageCount);
    }

    public function getById(int $id): User
    {
        $user =  User::query()
            ->with('roles')
            ->find($id);

        if (! $user) {
            throw new ModelNotFoundException("Пользователь с ID = $id не найден");
        }

        return $user;
    }

    public function createUser(array $data): User
    {
        $user = User::query()->create($data);

        if (Arr::has($data, 'roles')) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->getById($id);

        if (Arr::has($data, 'password')) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        if (Arr::has($data, 'roles')) {
            $user->syncRoles($data['roles']);
        }

        return $user->fresh('roles');
    }

    public function deleteUser(int $id): void
    {
        $user = $this->getById($id);
        $user->delete();
    }
}
