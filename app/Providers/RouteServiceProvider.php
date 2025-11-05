<?php

namespace App\Providers;

use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mapAllModulesApiRoutes();
    }

    /**
     * Ищет все модули и их api_v(N).php роуты
     */
    protected function mapAllModulesApiRoutes(): void
    {
        $modulesPath = base_path('app-modules');

        if (!is_dir($modulesPath)) {
            return;
        }

        $modules = scandir($modulesPath);

        foreach ($modules as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $routesDir = "{$modulesPath}/{$module}/routes";
            if (!is_dir($routesDir)) {
                continue;
            }
            $this->mapModuleApiRoutes($module, $routesDir);
        }
    }

    /**
     * Подключает все версии API для одного модуля
     */
    protected function mapModuleApiRoutes(string $module, string $routesDir): void
    {
        $routeFiles = scandir($routesDir);

        foreach ($routeFiles as $file) {
            if (preg_match('/^api_v(\d+)\.php$/', $file, $matches)) {
                $version = (int)$matches[1];
                $routeFile = "{$routesDir}/{$file}";

                if (!file_exists($routeFile)) {
                    continue;
                }

                Route::middleware([SubstituteBindings::class])
                    ->prefix("api/v{$version}")
                    ->group($routeFile);
            }
        }
    }
}
