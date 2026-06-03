<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadHelpers();
    }

    public function boot(): void
    {
        $this->loadRoutes();
    }

    protected function loadHelpers(): void
    {
        $helpersPath = app_path('Helpers/helpers.php');
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(base_path('routes/admin.php'));
        $this->loadRoutesFrom(base_path('routes/vendor.php'));
    }
}
