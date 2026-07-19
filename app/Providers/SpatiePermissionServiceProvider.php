<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

/**
 * This provider loads config BEFORE Spatie\Permission\PermissionServiceProvider
 */
class SpatiePermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // CRITICAL: Set ALL permission cache config BEFORE Spatie loads
        config([
            'permission.cache.key' => 'spatie.permission.cache',
            'permission.cache.store' => 'array',
            'permission.cache.expiration_time' => \DateInterval::createFromDateString('0 seconds'),
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
        // Re-set the cache configuration in boot
        config([
            'permission.cache.key' => 'spatie.permission.cache',
            'permission.cache.store' => 'array',
            'permission.cache.expiration_time' => \DateInterval::createFromDateString('0 seconds'),
        ]);
        
        // Forget any cached permissions
        if ($this->app->bound(PermissionRegistrar::class)) {
            $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
        }
    }
}
