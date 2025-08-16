<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\RoleNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Carbon;

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
        Carbon::setLocale('id');
        App::setLocale('id');
        View::composer('*', function ($view) {
            if (Auth::check()) {
                // Ambil semua notifikasi (tanpa take/limit)
                $notifications = RoleNotification::where('id_role', Auth::user()->id_role)
                    ->latest()
                    ->get();

                $unreadCount = $notifications->where('is_read', false)->count();

                $view->with('notifications', $notifications)
                    ->with('unreadNotificationCount', $unreadCount);
            }
        });
    }
}
