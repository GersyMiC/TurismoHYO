<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

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
    public function boot(Router $router)
    {
        parent::boot($router);

        // Aquí puedes agregar middleware global o de rutas específicas
        $router->middlewareGroup('admin', [
            \App\Http\Middleware\CheckAdmin::class,
        ]);
    }
}
