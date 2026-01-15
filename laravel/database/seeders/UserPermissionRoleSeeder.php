<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserPermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            ['name' => 'user.delete', 'description' => 'Delete Users']
        ];

        foreach ($permissions as $p) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $p['name']],
                [
                    'description' => $p['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $rolePermissions = [
            'admin' => [
                'user.delete'
            ]
        ];

        $pivotInserts = [];
        foreach ($rolePermissions as $roleName => $perms) {
            $roleId = DB::table('roles')->where('name', $roleName)->value('id');
            if (! $roleId) {
                continue;
            }

            foreach ($perms as $permName) {
                $permissionId = DB::table('permissions')->where('name', $permName)->value('id');
                if ($permissionId) {
                    $pivotInserts[] = [
                        'role_id' => $roleId,
                        'permission_id' => $permissionId,
                    ];
                }
            }
        }

        if (!empty($pivotInserts)) {
            DB::table('permission_role')->insertOrIgnore($pivotInserts);
        }
    }
}
