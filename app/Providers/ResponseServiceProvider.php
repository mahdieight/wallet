<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ResponseService;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('response', function(){
            return new ResponseService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
