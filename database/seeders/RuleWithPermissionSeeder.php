<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RuleWithPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userPermissions = [
            'users' => [
                'view',
                'create',
                'edit',
            ],
        ];
        $categoryPermissions = [
            'categories' => [
                'view',
                'create',
                'edit',
                'delete',
            ],
        ];
        $placePermissions = [
            'places' => [
                'view',
                'create',
                'edit',
                'delete',
            ],
        ];
        $reportPermissions = [
            'reports' => [
                'view',
                'create',
                'edit',
            ],
        ];
        $reviewPermissions = [
            'reviews' => [
                'view',
                'create',
                'edit',
            ],
        ];
        $rules = [
            'administrator' => [
                ...$userPermissions,
                ...$categoryPermissions,
                ...$placePermissions,
                ...$reportPermissions,
                ...$reviewPermissions,
            ],
            'manager' => [
                ...$categoryPermissions,
                ...$placePermissions,
                ...$reportPermissions,
                ...$reviewPermissions,
            ],
            'user' => [
                'reports' => [
                    'view',
                    'create'
                ],
                'reviews' => [
                    'view',
                    'create'
                ],
            ]
        ];
        foreach ($rules['administrator'] as $model => $actions) {
            foreach ($actions as $action) {
                Permission::query()->create(['name' => "{$model}.{$action}"]);
            }
        }
        foreach ($rules as $role => $permissions) {
            $role = Role::create(['name' => $role]);
            foreach ($permissions as $model => $action) {
                $role->givePermissionTo(
                    array_map(fn($action) => "{$model}.{$action}", $actions)
                );
            }
        }
    }
}

