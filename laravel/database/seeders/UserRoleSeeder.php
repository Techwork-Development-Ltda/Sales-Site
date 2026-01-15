<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrator',
                'email' => 't3stemail3nvio01@gmail.com',
                'password' => Hash::make('Test@1234'),
            ],
            [
                'name' => 'Manager',
                'email' => 't3stemail3nvio02@gmail.com',
                'password' => Hash::make('Test@1234'),
            ],
            [
                'name' => 'User',
                'email' => 't3stemail3nvio03@gmail.com',
                'password' => Hash::make('Test@1234'),
            ],
        ];

        foreach ($users as $u) {
            DB::table('users')->updateOrInsert(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => $u['password'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $roles = [
            ['name' => 'admin', 'description' => 'System Administrator'],
            ['name' => 'manager', 'description' => 'Manager / Supervisor'],
            ['name' => 'user', 'description' => 'Standard User'],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(
                ['name' => $r['name']],
                [
                    'description' => $r['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $assignments = [
            't3stemail3nvio01@gmail.com' => 'admin',
            't3stemail3nvio02@gmail.com' => 'manager',
            't3stemail3nvio03@gmail.com' => 'user',
        ];

        $pivotInserts = [];
        foreach ($assignments as $email => $roleName) {
            $userId = DB::table('users')->where('email', $email)->value('id');
            $roleId = DB::table('roles')->where('name', $roleName)->value('id');

            if ($userId && $roleId) {
                $pivotInserts[] = [
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ];
            }
        }

        if (!empty($pivotInserts)) {
            DB::table('role_user')->insertOrIgnore($pivotInserts);
        }
    
    }
}
