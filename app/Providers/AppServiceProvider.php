<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // as noted in the Listing model regarding turning off guard mode for mass inputs on forms, we are using this boot turn off default method targetting the Model superclass and deploying its unguard() meth to do so.
            Model::unguard();
    }
}
