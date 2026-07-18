<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
        'group_name',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    public static function defaultPermissions(): array
    {
        return [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            
            // Member Management
            'members.view',
            'members.create',
            'members.edit',
            'members.delete',
            'members.export',
            'members.card',
            
            // Contribution Management
            'contributions.view',
            'contributions.create',
            'contributions.edit',
            'contributions.delete',
            'contributions.process',
            
            // Emergency Collection
            'emergency.view',
            'emergency.create',
            'emergency.edit',
            'emergency.delete',
            
            // Payment Management
            'payments.view',
            'payments.create',
            'payments.process',
            'payments.refund',
            
            // Donation Management
            'donations.view',
            'donations.create',
            'donations.manage',
            
            // Accounting
            'accounting.view',
            'accounting.income',
            'accounting.expense',
            'accounting.voucher',
            'accounting.ledger',
            
            // Reports
            'reports.view',
            'reports.financial',
            'reports.member',
            'reports.export',
            
            // Settings
            'settings.view',
            'settings.update',
            'settings.cms',
            
            // Blood Donors
            'blood_donors.view',
            'blood_donors.manage',
            
            // Events
            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            
            // Notices
            'notices.view',
            'notices.create',
            'notices.edit',
            'notices.delete',
            
            // Documents
            'documents.view',
            'documents.upload',
            'documents.delete',
            
            // Gallery
            'gallery.view',
            'gallery.manage',
            
            // Activities
            'activities.view',
            'activities.manage',
            
            // Dashboard
            'dashboard.view',
        ];
    }
}
