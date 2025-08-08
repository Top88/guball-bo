<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = [
            [
                'name' => 'super-admin',
                'display_name' => 'Super Admin',
                'description' => 'Super Admin Role',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Admin Role',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'User Role',
                'guard_name' => 'web',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Role::insert($role);
    }
}
