<?php

namespace Noox\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Broadcast;
use Noox\Http\Middleware\JWTMultiAuth;
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
        Broadcast::routes(['middleware' => [ 'api', CorsHandler::class, JWTMultiAuth::class ]]);

        require base_path('routes/channels.php');
    }
}
