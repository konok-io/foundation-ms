<?php

return [
    'models' => [
        'permission' => App\Models\Permission::class,
        'role' => App\Models\Role::class,
    ],
    'table_names' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',
    ],
    'column_names' => [
        'role' => 'name',
        'description' => 'description',
        'guard_name' => 'guard_name',
        'model_morph_key' => 'model_id',
        'team_column_name' => 'team_id',
    ],
    'display_permission_in_exception' => false,
    'display_role_in_exception' => false,
    'default_guard_name' => 'web',
    'Teams' => [
        'enabled' => false,
        'value' => null,
    ],
    'created_at' => null,
    'updated_at' => null,

    'cache' => [
        'key' => 'spatie.permission.cache',
        'expiration_time' => \DateInterval::createFromDateString('0 seconds'),
        'store' => 'array',
    ],
];
