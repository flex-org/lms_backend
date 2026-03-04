<?php

namespace App\Modules;

use Illuminate\Support\ServiceProvider;

class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        foreach (config('modules.providers', []) as $provider) {
            $this->app->register($provider);
        }
    }
}
