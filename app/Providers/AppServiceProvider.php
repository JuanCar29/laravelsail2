<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Policies\ReciboPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Preferencia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
       Gate::define('admin', function (User $user) {
           return $user->tieneNivel(1);
       });
       Gate::define('camarero', function (User $user) {
           return $user->tieneNivel(1,2);
       });
       Gate::define('cocinero', function (User $user) {
           return $user->tieneNivel(1,3);
       });

       if (!app()->runningInConsole() && Schema::hasTable('preferencias')) {
            View::share('miBar', Preferencia::first());
       }
    }
}
