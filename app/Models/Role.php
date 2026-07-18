<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_has_permissions');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'model_has_roles', 'role_id', 'model_id');
    }

    public static function defaultRoles(): array
    {
        return [
            'Super Admin' => 'Full system access with all permissions',
            'Admin' => 'Administrative access with most permissions',
            'Accountant' => 'Financial management and reporting access',
            'Executive Member' => 'Executive level access for decision making',
            'General Member' => 'Basic member access',
            'Volunteer' => 'Volunteer access for event management',
            'Donor' => 'Donor access for donation history',
        ];
    }
}
