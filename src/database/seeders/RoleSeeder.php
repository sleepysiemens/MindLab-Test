<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'guard_name' => 'api'],
            ['name' => 'manager', 'guard_name' => 'api'],
            ['name' => 'customer', 'guard_name' => 'api'],
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate($role);
        }
    }
}
