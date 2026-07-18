<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'Super Admin' => [
                'description' => 'Full system access with all permissions',
                'permissions' => Permission::pluck('id')->toArray(),
            ],
            'Admin' => [
                'description' => 'Administrative access with most permissions',
                'permissions' => Permission::whereNotIn('name', [
                    'users.delete',
                    'roles.delete',
                ])->pluck('id')->toArray(),
            ],
            'Accountant' => [
                'description' => 'Financial management and reporting access',
                'permissions' => Permission::whereIn('group_name', [
                    'Contributions',
                    'Emergency',
                    'Payments',
                    'Donations',
                    'Accounting',
                    'Reports',
                    'Dashboard',
                ])->pluck('id')->toArray(),
            ],
            'Executive Member' => [
                'description' => 'Executive level access for decision making',
                'permissions' => Permission::whereIn('name', [
                    'dashboard.view',
                    'members.view',
                    'contributions.view',
                    'emergency.view',
                    'donations.view',
                    'reports.view',
                    'events.view',
                    'notices.view',
                    'activities.view',
                ])->pluck('id')->toArray(),
            ],
            'General Member' => [
                'description' => 'Basic member access',
                'permissions' => Permission::whereIn('name', [
                    'dashboard.view',
                    'notices.view',
                ])->pluck('id')->toArray(),
            ],
            'Volunteer' => [
                'description' => 'Volunteer access for event management',
                'permissions' => Permission::whereIn('name', [
                    'dashboard.view',
                    'events.view',
                    'events.create',
                    'members.view',
                    'notices.view',
                ])->pluck('id')->toArray(),
            ],
            'Donor' => [
                'description' => 'Donor access for donation history',
                'permissions' => Permission::whereIn('name', [
                    'dashboard.view',
                    'donations.view',
                ])->pluck('id')->toArray(),
            ],
        ];

        foreach ($roles as $name => $data) {
            $role = Role::firstOrCreate(
                ['name' => $name],
                [
                    'guard_name' => 'web',
                    'description' => $data['description'],
                ]
            );

            $role->syncPermissions($data['permissions']);
        }

        $this->command->info('Roles seeded successfully!');
    }
}
