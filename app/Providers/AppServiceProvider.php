<?php

namespace App\Providers;

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
        try {
            if (\Schema::hasTable('permissions')) {
                \App\Models\Permission::where('permission_status', 'Pending')
                    ->where('created_at', '<', now()->subDays(7))
                    ->update(['permission_status' => 'Approved']);
            }
        } catch (\Exception $e) {
            // Prevent issues during database migration/initial setup
        }
    }
}
