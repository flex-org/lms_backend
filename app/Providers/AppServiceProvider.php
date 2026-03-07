<?php

namespace App\Providers;

use App\Facades\ApiResponse;
use App\Facades\FacadesLogic\ApiResponseLogic;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ApiResponse::class,
            ApiResponseLogic::class
        );
    }

    /**
     * Route groups that do NOT require tenant (domain) resolution.
     */
    private array $publicRoutes = [
        ['prefix' => '',      'file' => 'api.php'],
        ['prefix' => 'enums', 'file' => 'enums.php'],
    ];

    /**
     * Route groups that REQUIRE tenant (domain) resolution.
     * domainExists middleware is applied automatically.
     */
    private array $tenantRoutes = [
        ['prefix' => 'dashboard', 'file' => 'dashboard.php'],
        ['prefix' => 'portal',    'file' => 'portal.php'],
        ['prefix' => 'builder',   'file' => 'builder.php'],
        ['prefix' => 'test',      'file' => 'feature-test.php'],
    ];

    public function boot(): void
    {
        $this->registerRoutes();
    }

    private function registerRoutes(): void
    {
        foreach ($this->publicRoutes as $route) {
            Route::middleware(['api', 'locale'])
                ->prefix('api/v1' . ($route['prefix'] ? '/' . $route['prefix'] : ''))
                ->group(base_path('routes/V1/' . $route['file']));
        }

        foreach ($this->tenantRoutes as $route) {
            Route::middleware(['api', 'domainExists', 'locale'])
                ->prefix('api/v1/' . $route['prefix'])
                ->group(base_path('routes/V1/' . $route['file']));
        }
    }
}
