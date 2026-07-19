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
        config(['permission.cache.store' => 'array']);
        $permissions = [
            // Dashboard
            ['name' => 'dashboard.view', 'group_name' => 'Dashboard', 'description' => 'View dashboard'],
            
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
            
            // Emergency Collections
            ['name' => 'emergency_collections.view', 'group_name' => 'Emergency', 'description' => 'View emergency collections'],
            ['name' => 'emergency_collections.create', 'group_name' => 'Emergency', 'description' => 'Create emergency collections'],
            ['name' => 'emergency_collections.edit', 'group_name' => 'Emergency', 'description' => 'Edit emergency collections'],
            ['name' => 'emergency_collections.delete', 'group_name' => 'Emergency', 'description' => 'Delete emergency collections'],
            
            // Payment Management
            ['name' => 'payments.view', 'group_name' => 'Payments', 'description' => 'View payments'],
            ['name' => 'payments.create', 'group_name' => 'Payments', 'description' => 'Create payments'],
            ['name' => 'payments.process', 'group_name' => 'Payments', 'description' => 'Process payments'],
            ['name' => 'payments.refund', 'group_name' => 'Payments', 'description' => 'Refund payments'],
            ['name' => 'payments.export', 'group_name' => 'Payments', 'description' => 'Export payments'],
            
            // Donation Management
            ['name' => 'donations.view', 'group_name' => 'Donations', 'description' => 'View donations'],
            ['name' => 'donations.create', 'group_name' => 'Donations', 'description' => 'Create donations'],
            ['name' => 'donations.edit', 'group_name' => 'Donations', 'description' => 'Edit donations'],
            ['name' => 'donations.delete', 'group_name' => 'Donations', 'description' => 'Delete donations'],
            ['name' => 'donations.export', 'group_name' => 'Donations', 'description' => 'Export donations'],
            
            // Receipts
            ['name' => 'receipts.view', 'group_name' => 'Receipts', 'description' => 'View receipts'],
            ['name' => 'receipts.download', 'group_name' => 'Receipts', 'description' => 'Download receipts'],
            ['name' => 'receipts.email', 'group_name' => 'Receipts', 'description' => 'Email receipts'],
            ['name' => 'receipts.export', 'group_name' => 'Receipts', 'description' => 'Export receipts'],
            
            // Income Management
            ['name' => 'incomes.view', 'group_name' => 'Income', 'description' => 'View income'],
            ['name' => 'incomes.create', 'group_name' => 'Income', 'description' => 'Create income'],
            ['name' => 'incomes.edit', 'group_name' => 'Income', 'description' => 'Edit income'],
            ['name' => 'incomes.delete', 'group_name' => 'Income', 'description' => 'Delete income'],
            
            // Income Categories
            ['name' => 'income_categories.view', 'group_name' => 'Income Categories', 'description' => 'View income categories'],
            ['name' => 'income_categories.create', 'group_name' => 'Income Categories', 'description' => 'Create income categories'],
            ['name' => 'income_categories.edit', 'group_name' => 'Income Categories', 'description' => 'Edit income categories'],
            ['name' => 'income_categories.delete', 'group_name' => 'Income Categories', 'description' => 'Delete income categories'],
            
            // Expense Management
            ['name' => 'expenses.view', 'group_name' => 'Expenses', 'description' => 'View expenses'],
            ['name' => 'expenses.create', 'group_name' => 'Expenses', 'description' => 'Create expenses'],
            ['name' => 'expenses.edit', 'group_name' => 'Expenses', 'description' => 'Edit expenses'],
            ['name' => 'expenses.delete', 'group_name' => 'Expenses', 'description' => 'Delete expenses'],
            
            // Expense Categories
            ['name' => 'expense_categories.view', 'group_name' => 'Expense Categories', 'description' => 'View expense categories'],
            ['name' => 'expense_categories.create', 'group_name' => 'Expense Categories', 'description' => 'Create expense categories'],
            ['name' => 'expense_categories.edit', 'group_name' => 'Expense Categories', 'description' => 'Edit expense categories'],
            ['name' => 'expense_categories.delete', 'group_name' => 'Expense Categories', 'description' => 'Delete expense categories'],
            
            // Ledger
            ['name' => 'ledger.view', 'group_name' => 'Ledger', 'description' => 'View ledger'],
            ['name' => 'ledger.export', 'group_name' => 'Ledger', 'description' => 'Export ledger'],
            
            // Reports
            ['name' => 'reports.view', 'group_name' => 'Reports', 'description' => 'View reports'],
            
            // Settings
            ['name' => 'settings.view', 'group_name' => 'Settings', 'description' => 'View settings'],
            ['name' => 'settings.update', 'group_name' => 'Settings', 'description' => 'Update settings'],
            ['name' => 'settings.edit', 'group_name' => 'Settings', 'description' => 'Edit settings'],
            ['name' => 'settings.cms', 'group_name' => 'Settings', 'description' => 'Manage CMS'],
            
            // Blood Donors
            ['name' => 'blood_donors.view', 'group_name' => 'Blood Donors', 'description' => 'View blood donors'],
            ['name' => 'blood_donors.edit', 'group_name' => 'Blood Donors', 'description' => 'Edit blood donors'],
            
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
            ['name' => 'documents.create', 'group_name' => 'Documents', 'description' => 'Create documents'],
            ['name' => 'documents.upload', 'group_name' => 'Documents', 'description' => 'Upload documents'],
            ['name' => 'documents.delete', 'group_name' => 'Documents', 'description' => 'Delete documents'],
            ['name' => 'documents.verify', 'group_name' => 'Documents', 'description' => 'Verify documents'],
            
            // Gallery
            ['name' => 'gallery.view', 'group_name' => 'Gallery', 'description' => 'View gallery'],
            ['name' => 'gallery.manage', 'group_name' => 'Gallery', 'description' => 'Manage gallery'],
            
            // Activities
            ['name' => 'activities.view', 'group_name' => 'Activities', 'description' => 'View activities'],
            ['name' => 'activities.create', 'group_name' => 'Activities', 'description' => 'Create activities'],
            ['name' => 'activities.edit', 'group_name' => 'Activities', 'description' => 'Edit activities'],
            ['name' => 'activities.delete', 'group_name' => 'Activities', 'description' => 'Delete activities'],
            
            // Notifications
            ['name' => 'notifications.view', 'group_name' => 'Notifications', 'description' => 'View notifications'],
            ['name' => 'notifications.create', 'group_name' => 'Notifications', 'description' => 'Create notifications'],
            ['name' => 'notifications.delete', 'group_name' => 'Notifications', 'description' => 'Delete notifications'],
            
            // Audit Logs
            ['name' => 'audit-logs.view', 'group_name' => 'Audit Logs', 'description' => 'View audit logs'],
            ['name' => 'audit-logs.delete', 'group_name' => 'Audit Logs', 'description' => 'Delete audit logs'],
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
