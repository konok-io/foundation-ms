<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Spatie\Activitylog\ActivitylogServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(PermissionServiceProvider::class);
        $this->app->register(ActivitylogServiceProvider::class);
    }

    public function boot(): void
    {
        //
    }
}
