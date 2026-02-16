<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UserServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserService implements UserServiceInterface
{
    public function paginate(int $onPageCount = 15, int $currPage = 1): LengthAwarePaginator
    {
        $params = [
            'on_page' => $onPageCount,
            'page'    => $currPage,
        ];

       return Cache::tags(['users'])->rememberForever(
           'users:' . md5(json_encode($params)),
           function () use ($onPageCount) {
               return User::query()
                   ->select(['id', 'email', 'name', 'is_active', 'created_at'])
                   ->with('roles')
                   ->orderBy('id')
                   ->paginate($onPageCount);
           }
       );
    }

    /**
     * @throws Throwable
     */
    public function getById(int $id): User
    {
        return Cache::tags(['users'])->rememberForever(
            "user:$id",
            function () use ($id) {
                $user = User::query()
                    ->with('roles')
                    ->find($id);

                throw_if(
                    ! $user,
                    new ModelNotFoundException("Пользователь с ID = $id не найден")
                );

                return $user;
            }
        );
    }

    public function createUser(array $data): User
    {
        $user = User::query()->create($data);

        if (Arr::has($data, 'roles')) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    /**
     * @throws Throwable
     */
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

    /**
     * @throws Throwable
     */
    public function deleteUser(int $id): void
    {
        $user = $this->getById($id);
        $user->delete();
    }

    /**
     * @throws Throwable
     */
    public function deactivateUser(int $id): User
    {
        return $this->updateUser($id, ['is_active' => 0]);
    }
}
