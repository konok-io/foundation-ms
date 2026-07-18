<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'username' => 'superadmin',
                'phone' => '+8801700000000',
                'password' => 'password',
                'status' => 'active',
                'role' => 'Super Admin',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@foundation.org',
                'username' => 'admin',
                'phone' => '+8801700000001',
                'password' => 'password',
                'status' => 'active',
                'role' => 'Admin',
            ],
            [
                'name' => 'Accountant User',
                'email' => 'accountant@foundation.org',
                'username' => 'accountant',
                'phone' => '+8801700000002',
                'password' => 'password',
                'status' => 'active',
                'role' => 'Accountant',
            ],
            [
                'name' => 'Executive Member',
                'email' => 'executive@foundation.org',
                'username' => 'executive',
                'phone' => '+8801700000003',
                'password' => 'password',
                'status' => 'active',
                'role' => 'Executive Member',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'phone' => $userData['phone'],
                    'password' => Hash::make($userData['password']),
                    'status' => $userData['status'],
                ]
            );

            $user->assignRole($role);
        }

        $this->command->info('Users seeded successfully!');
    }
}
