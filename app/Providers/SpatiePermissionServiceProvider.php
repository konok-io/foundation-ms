<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

/**
 * This provider MUST be registered BEFORE Spatie\Permission\PermissionServiceProvider
 * to ensure that the cache.store is set to 'array' to avoid database table requirements.
 */
class SpatiePermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // CRITICAL: Set cache store to array BEFORE Spatie loads
        // This prevents the "Table 'cache' doesn't exist" error
        config([
            'permission.cache.store' => 'array',
            'permission.cache.expiration_time' => 0,
            'cache.default' => 'array',
            'cache.store' => 'array',
        ]);
        
        // Register the original Spatie Permission Service Provider
        $this->app->register(\Spatie\Permission\PermissionServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Re-set the cache configuration in boot to ensure it takes effect
        config([
            'permission.cache.store' => 'array',
            'permission.cache.expiration_time' => 0,
        ]);
        
        // Forget any cached permissions to ensure fresh load
        if ($this->app->bound(PermissionRegistrar::class)) {
            $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
