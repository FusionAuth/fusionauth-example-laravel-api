<?php

declare(strict_types=1);

namespace App\FusionAuth\Providers;

use App\FusionAuth\FusionAuthJWTGuard;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class FusionAuthServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        Auth::provider('fusionauth_eloquent', function (Application $app, array $config) {
            return new FusionAuthEloquentUserProvider($this->app['hash'], $config['model']);
        });
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            $guard = new FusionAuthJWTGuard(
                $app['tymon.jwt'],
                $app['auth']->createUserProvider($config['provider']),
                $app['request']
            );

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }

}
