<?php
declare(strict_types=1);

namespace App\Interfaces;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function paginate(int $onPageCount = 15): LengthAwarePaginator;

    public function getById(int $id): User;

    public function createUser(array $data): User;
}
