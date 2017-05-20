<?php

namespace Noox\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use \Tymon\JWTAuth\Middleware\GetUserFromToken as JWTAuthentication;
use \Barryvdh\Cors\HandleCors as CorsHandler;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => [ 'api', CorsHandler::class, JWTAuthentication::class ]]);

        require base_path('routes/channels.php');
    }
}
