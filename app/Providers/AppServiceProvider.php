<?php

namespace App\Providers;

use App\Models\GeneralSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;
use Spatie\Activitylog\ActivitylogServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Note: Spatie Permission is now registered by SpatiePermissionServiceProvider
        $this->app->register(ActivitylogServiceProvider::class);
    }

    public function boot(): void
    {
        // Use Bootstrap 5 pagination style
        Paginator::useBootstrapFive();

        // Share GeneralSetting with all views
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('general_settings')) {
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
            } else {
                view()->share('siteSettings', []);
            }
        } catch (\Exception $e) {
            // If table doesn't exist yet, just continue without settings
            view()->share('siteSettings', []);
        }
    }
}
