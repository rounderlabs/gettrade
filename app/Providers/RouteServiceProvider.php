<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/dashboard';

    public function boot()
    {
        $this->configureRateLimiting();

        // Dynamically switch Inertia layout based on route prefix
        app('router')->matched(function () {
            if (request()->is('admin/*')) {
                Inertia::setRootView('admin');
            } else {
                Inertia::setRootView('app');
            }
        });

        $this->routes(function () {
            // API
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Web (user)
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            // Admin
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        });
    }


    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
