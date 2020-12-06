<?php

namespace App\Providers;

use App\Http\Container\LocationContainer;
use App\Http\Contract\LocationContract;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LocationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Binding profile contract to it's container
        App::bind(LocationContract::class,function (){
           return new LocationContainer();
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
