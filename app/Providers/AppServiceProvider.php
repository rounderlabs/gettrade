<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Tighten\Ziggy\Ziggy;

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
        // Use different root layouts for admin vs user routes
        Inertia::setRootView(Request::is('admin/*') ? 'admin' : 'app');

        // Share common props
        Inertia::share([
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => url()->current(),
            ],

            // Default authenticated user (web guard)
            'auth' => [
                'user' => fn () => Auth::guard('web')->user()
                    ? [
                        'id' => Auth::guard('web')->id(),
                        'name' => Auth::guard('web')->user()->name,
                        'email' => Auth::guard('web')->user()->email,
                    ]
                    : null,
            ],

            // Admin user (admin guard)
            'admin' => fn () => Auth::guard('admin')->check()
                ? [
                    'id' => Auth::guard('admin')->id(),
                    'name' => Auth::guard('admin')->user()->name,
                    'email' => Auth::guard('admin')->user()->email
                ]
                : null,
        ]);
    }
}
