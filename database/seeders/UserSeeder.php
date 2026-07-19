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
        // Only Super Admin
        $user = User::firstOrCreate(
            ['email' => 'admin@konok.io'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'phone' => '+8801700000000',
                'password' => Hash::make('@rsm@k@1A'),
                'status' => 'active',
            ]
        );

        // Ensure Super Admin role exists and assign it
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $user->assignRole($role);

        $this->command->info('Super Admin created: admin@konok.io');
    }
}
