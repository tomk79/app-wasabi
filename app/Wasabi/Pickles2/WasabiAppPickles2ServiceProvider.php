<?php

namespace App\Wasabi\Pickles2;

use Illuminate\Support\ServiceProvider;

class WasabiAppPickles2ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/views', 'pickles2');
    }
}
