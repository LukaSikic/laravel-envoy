<?php

namespace Luka\Envoy;

use Illuminate\Support\ServiceProvider;

class EnvoyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/envoy.php',
            'envoy'
        );

        $this->publishes([
            __DIR__ . '/config/envoy.php' => config_path('envoy.php')
        ]);
    }

    public function register()
    {
        $this->app->bind('laravel-envoy', function(){
            return new Worker();
        });
    }

}