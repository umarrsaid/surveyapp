<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class IffServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // \Simlending::checkScoring();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('iff', function()
        {
            return new \App\Classes\Iff;
        });
    }
}
