<?php

namespace App\Wasabi\AbstractTaskmanager;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
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
        // ビューを登録
        $this->loadViewsFrom(__DIR__.'/views', 'App\Wasabi\AbstractTaskmanager');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
    }
}
