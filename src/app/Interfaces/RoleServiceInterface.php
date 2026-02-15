<?php

namespace App\Interfaces;

use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

interface RoleServiceInterface
{
    public function paginate(int $onPageCount = 15): LengthAwarePaginator;

    public function getById(int $id): Role;

    public function createRole(array $data): Role;

    public function updateRole(int $id, array $data): Role;

    public function deleteRole(int $id): void;
}
