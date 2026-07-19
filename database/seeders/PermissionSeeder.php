<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Use array cache instead of database cache
        config(['permission.cache.store' => 'array']);
        $permissions = [
            // User Management
            ['name' => 'users.view', 'group_name' => 'User Management', 'description' => 'View users'],
            ['name' => 'users.create', 'group_name' => 'User Management', 'description' => 'Create users'],
            ['name' => 'users.edit', 'group_name' => 'User Management', 'description' => 'Edit users'],
            ['name' => 'users.delete', 'group_name' => 'User Management', 'description' => 'Delete users'],
            
            // Role Management
            ['name' => 'roles.view', 'group_name' => 'Role Management', 'description' => 'View roles'],
            ['name' => 'roles.create', 'group_name' => 'Role Management', 'description' => 'Create roles'],
            ['name' => 'roles.edit', 'group_name' => 'Role Management', 'description' => 'Edit roles'],
            ['name' => 'roles.delete', 'group_name' => 'Role Management', 'description' => 'Delete roles'],
            
            // Member Management
            ['name' => 'members.view', 'group_name' => 'Member Management', 'description' => 'View members'],
            ['name' => 'members.create', 'group_name' => 'Member Management', 'description' => 'Create members'],
            ['name' => 'members.edit', 'group_name' => 'Member Management', 'description' => 'Edit members'],
            ['name' => 'members.delete', 'group_name' => 'Member Management', 'description' => 'Delete members'],
            ['name' => 'members.export', 'group_name' => 'Member Management', 'description' => 'Export members'],
            ['name' => 'members.card', 'group_name' => 'Member Management', 'description' => 'Generate member cards'],
            
            // Contribution Management
            ['name' => 'contributions.view', 'group_name' => 'Contributions', 'description' => 'View contributions'],
            ['name' => 'contributions.create', 'group_name' => 'Contributions', 'description' => 'Create contributions'],
            ['name' => 'contributions.edit', 'group_name' => 'Contributions', 'description' => 'Edit contributions'],
            ['name' => 'contributions.delete', 'group_name' => 'Contributions', 'description' => 'Delete contributions'],
            ['name' => 'contributions.process', 'group_name' => 'Contributions', 'description' => 'Process contributions'],
            
            // Emergency Collection
            ['name' => 'emergency.view', 'group_name' => 'Emergency', 'description' => 'View emergency collections'],
            ['name' => 'emergency.create', 'group_name' => 'Emergency', 'description' => 'Create emergency collections'],
            ['name' => 'emergency.edit', 'group_name' => 'Emergency', 'description' => 'Edit emergency collections'],
            ['name' => 'emergency.delete', 'group_name' => 'Emergency', 'description' => 'Delete emergency collections'],
            
            // Payment Management
            ['name' => 'payments.view', 'group_name' => 'Payments', 'description' => 'View payments'],
            ['name' => 'payments.create', 'group_name' => 'Payments', 'description' => 'Create payments'],
            ['name' => 'payments.process', 'group_name' => 'Payments', 'description' => 'Process payments'],
            ['name' => 'payments.refund', 'group_name' => 'Payments', 'description' => 'Refund payments'],
            
            // Donation Management
            ['name' => 'donations.view', 'group_name' => 'Donations', 'description' => 'View donations'],
            ['name' => 'donations.create', 'group_name' => 'Donations', 'description' => 'Create donations'],
            ['name' => 'donations.manage', 'group_name' => 'Donations', 'description' => 'Manage donations'],
            
            // Accounting
            ['name' => 'accounting.view', 'group_name' => 'Accounting', 'description' => 'View accounting'],
            ['name' => 'accounting.income', 'group_name' => 'Accounting', 'description' => 'Manage income'],
            ['name' => 'accounting.expense', 'group_name' => 'Accounting', 'description' => 'Manage expenses'],
            ['name' => 'accounting.voucher', 'group_name' => 'Accounting', 'description' => 'Manage vouchers'],
            ['name' => 'accounting.ledger', 'group_name' => 'Accounting', 'description' => 'View ledger'],
            
            // Reports
            ['name' => 'reports.view', 'group_name' => 'Reports', 'description' => 'View reports'],
            ['name' => 'reports.financial', 'group_name' => 'Reports', 'description' => 'View financial reports'],
            ['name' => 'reports.member', 'group_name' => 'Reports', 'description' => 'View member reports'],
            ['name' => 'reports.export', 'group_name' => 'Reports', 'description' => 'Export reports'],
            
            // Settings
            ['name' => 'settings.view', 'group_name' => 'Settings', 'description' => 'View settings'],
            ['name' => 'settings.update', 'group_name' => 'Settings', 'description' => 'Update settings'],
            ['name' => 'settings.cms', 'group_name' => 'Settings', 'description' => 'Manage CMS'],
            
            // Blood Donors
            ['name' => 'blood_donors.view', 'group_name' => 'Blood Donors', 'description' => 'View blood donors'],
            ['name' => 'blood_donors.manage', 'group_name' => 'Blood Donors', 'description' => 'Manage blood donors'],
            
            // Events
            ['name' => 'events.view', 'group_name' => 'Events', 'description' => 'View events'],
            ['name' => 'events.create', 'group_name' => 'Events', 'description' => 'Create events'],
            ['name' => 'events.edit', 'group_name' => 'Events', 'description' => 'Edit events'],
            ['name' => 'events.delete', 'group_name' => 'Events', 'description' => 'Delete events'],
            
            // Notices
            ['name' => 'notices.view', 'group_name' => 'Notices', 'description' => 'View notices'],
            ['name' => 'notices.create', 'group_name' => 'Notices', 'description' => 'Create notices'],
            ['name' => 'notices.edit', 'group_name' => 'Notices', 'description' => 'Edit notices'],
            ['name' => 'notices.delete', 'group_name' => 'Notices', 'description' => 'Delete notices'],
            
            // Documents
            ['name' => 'documents.view', 'group_name' => 'Documents', 'description' => 'View documents'],
            ['name' => 'documents.upload', 'group_name' => 'Documents', 'description' => 'Upload documents'],
            ['name' => 'documents.delete', 'group_name' => 'Documents', 'description' => 'Delete documents'],
            
            // Gallery
            ['name' => 'gallery.view', 'group_name' => 'Gallery', 'description' => 'View gallery'],
            ['name' => 'gallery.manage', 'group_name' => 'Gallery', 'description' => 'Manage gallery'],
            
            // Activities
            ['name' => 'activities.view', 'group_name' => 'Activities', 'description' => 'View activities'],
            ['name' => 'activities.manage', 'group_name' => 'Activities', 'description' => 'Manage activities'],
            
            // Dashboard
            ['name' => 'dashboard.view', 'group_name' => 'Dashboard', 'description' => 'View dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                [
                    'guard_name' => 'web',
                    'description' => $permission['description'],
                    'group_name' => $permission['group_name'],
                ]
            );
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
