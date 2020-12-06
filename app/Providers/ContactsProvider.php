<?php

namespace App\Providers;

use App\Http\Container\ContactsContainer;
use App\Http\Contract\ContactsContract;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ContactsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Binding contacts contract and its container
        App::bind(ContactsContract::class,function (){
            return new ContactsContainer();
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
