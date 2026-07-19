<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\PermissionServiceProvider;
use Spatie\Permission\PermissionRegistrar;
use Spatie\Activitylog\ActivitylogServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(PermissionServiceProvider::class);
        $this->app->register(ActivitylogServiceProvider::class);
        
        // Set Spatie Permission to use array cache before package boots
        config(['permission.cache.store' => 'array']);
    }

    public function boot(): void
    {
        // Register Spatie permission cache to use array store
        $this->app->make(PermissionRegistrar::class)->forgetCachedPermissions();
        
        // Share GeneralSetting with all views
        try {
            $settings = GeneralSetting::getSettingsByGroup('general');
            $contactSettings = GeneralSetting::getSettingsByGroup('contact');
            $socialSettings = GeneralSetting::getSettingsByGroup('social');
            $appearanceSettings = GeneralSetting::getSettingsByGroup('appearance');
            
            $allSettings = array_merge($settings, $contactSettings, $socialSettings, $appearanceSettings);
            
            view()->share('siteSettings', $allSettings);
            
            // Also define a Blade directive for easy access
            Blade::directive('setting', function ($key) {
                return "<?php echo \\App\\Models\\GeneralSetting::getSetting({$key}, ''); ?>";
            });
        } catch (\Exception $e) {
            // If table doesn't exist yet, just continue without settings
            view()->share('siteSettings', []);
        }
    }
}
