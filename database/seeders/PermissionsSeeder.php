<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $data = [
            [
                'name' => 'access-admin',
                'group' => 'normal',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // User Management Page
            [
                'name' => 'view-manage-user',
                'group' => 'manage-user',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'create-user',
                'group' => 'manage-user',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'edit-user',
                'group' => 'manage-user',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'delete-user',
                'group' => 'manage-user',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'change-status-manage-user',
                'group' => 'manage-user',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // League Management Page
            [
                'name' => 'view-manage-league',
                'group' => 'manage-league',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'create-league',
                'group' => 'manage-league',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'edit-league',
                'group' => 'manage-league',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'delete-league',
                'group' => 'manage-league',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'view-manage-role',
                'group' => 'role',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'view-manage-team',
                'group' => 'team',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'create-team',
                'group' => 'team',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'edit-team',
                'group' => 'team',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'delete-team',
                'group' => 'team',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'view-manage-match',
                'group' => 'match',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'create-match',
                'group' => 'match',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'edit-match',
                'group' => 'match',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'delete-match',
                'group' => 'match',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'update-match-result',
                'group' => 'match',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'view-exchange-credit',
                'group' => 'exchange-credit',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'change-status-exchange-credit',
                'group' => 'exchange-credit',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'view-setting',
                'group' => 'settings',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'edit-setting',
                'group' => 'settings',
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        ];
        Permission::insert($data);
    }
}
