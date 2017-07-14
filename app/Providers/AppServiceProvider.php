<?php

namespace Noox\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Relation::morphMap([
            'news'    => 'Noox\Models\News',
            'user'    => 'Noox\Models\User',
            'comment' => 'Noox\Models\NewsComment',
            ]);

        app('Dingo\Api\Http\RateLimit\Handler')->setRateLimiter(function ($app, $request) {
            if (! $identifier = $request->header('Authorization')) {
                $identifier = $request->ip();
            }
            return sha1(implode('|', array_merge(
                $request->route()->methods(), [$request->route()->domain(), $request->route()->uri(), $identifier]
            )));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
