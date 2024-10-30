<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[
            {"name":"super_admin","guard_name":"web","permissions":
                [
                    "view_item",
                    "view_any_item",
                    "create_item",
                    "update_item",
                    "restore_item",
                    "restore_any_item",
                    "replicate_item",
                    "reorder_item",
                    "delete_item",
                    "delete_any_item",
                    "force_delete_item",
                    "force_delete_any_item",

                    "view_item::inspection",
                    "view_any_item::inspection",
                    "create_item::inspection",
                    "update_item::inspection",
                    "restore_item::inspection",
                    "restore_any_item::inspection",
                    "replicate_item::inspection",
                    "reorder_item::inspection",
                    "delete_item::inspection",
                    "delete_any_item::inspection",
                    "force_delete_item::inspection",
                    "force_delete_any_item::inspection",
                    
                    "view_item::template",
                    "view_any_item::template",
                    "create_item::template",
                    "update_item::template",
                    "restore_item::template",
                    "restore_any_item::template",
                    "replicate_item::template",
                    "reorder_item::template",
                    "delete_item::template",
                    "delete_any_item::template",
                    "force_delete_item::template",
                    "force_delete_any_item::template",
                    
                    "view_media",
                    "view_any_media",
                    "create_media",
                    "update_media",
                    "restore_media",
                    "restore_any_media",
                    "replicate_media",
                    "reorder_media",
                    "delete_media",
                    "delete_any_media",
                    "force_delete_media",
                    "force_delete_any_media",
                    
                    "view_role",
                    "view_any_role",
                    "create_role",
                    "update_role",
                    "delete_role",
                    "delete_any_role",
                    
                    "view_user",
                    "view_any_user",
                    "create_user",
                    "update_user",
                    "delete_user",
                    "delete_any_user",
                    
                    "page_ViewItem"
                ]
            },
            {"name":"panel_user","guard_name":"web","permissions":
                []
            }
        ]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
