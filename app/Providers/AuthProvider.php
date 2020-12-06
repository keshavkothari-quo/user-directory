<?php

namespace App\Providers;

use App\Http\Container\AuthContainer;
use App\Http\Contract\AuthContract;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Binding auth contract to container
        App::bind(AuthContract::class,function () {
            return new AuthContainer();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
