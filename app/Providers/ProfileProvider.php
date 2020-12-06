<?php

namespace App\Providers;

use App\Http\Container\ProfileContainer;
use App\Http\Contract\ProfileContract;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ProfileProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Binding profile contract to it's container
        App::bind(ProfileContract::class,function (){
           return new ProfileContainer();
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
