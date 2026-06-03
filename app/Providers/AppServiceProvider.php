<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('public-forms', function (Request $request) {
            return Limit::perMinute(8)->by($request->ip());
        });

        View::composer('*', function ($view): void {
            $settings = Cache::remember('settings.public', 3600, function () {
                if (! class_exists(Setting::class)) {
                    return collect();
                }

                try {
                    return Setting::query()
                        ->where('is_public', true)
                        ->get()
                        ->mapWithKeys(fn (Setting $setting) => [$setting->key => $setting->cast_value])
                        ->all();
                } catch (\Throwable) {
                    return [];
                }
            });

            $view->with('siteSettings', $settings);
        });
    }
}
