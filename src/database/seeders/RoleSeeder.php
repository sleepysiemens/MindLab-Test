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
            ['name' => 'admin'],
            ['name' => 'manager'],
            ['name' => 'customer'],
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate($role);
        }
    }
}
