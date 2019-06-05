<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Passport のデータベース定義をカスタマイズするので、
        // デフォルトの定義でmigrationされないようにする。
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Passport の client_id を UUID にするために追加した。
        Client::creating(function (Client $client) {
            $client->incrementing = false;
            $client->id = \Ramsey\Uuid\Uuid::uuid4()->toString();
        });

        // Passportの認証機能を制限して使う
        Passport::routes(function($router){
            // $router->forAuthorization();
            $router->forAccessTokens();
            // $router->forTransientTokens();
            // $router->forClients();
            // $router->forPersonalAccessTokens();
        });
    }
}
