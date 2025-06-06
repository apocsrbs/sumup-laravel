<?php

namespace Sumup\Laravel;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Sumup\Laravel\SumUpClient;

class SumUpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/sumup.php', 'sumup');

        $this->app->singleton('sumup', function ($app) {
            return new SumUpClient(
                Config::get('sumup.client_id') ?? null,
                Config::get('sumup.client_secret') ?? null,
                Config::get('sumup.redirect_uri') ?? null,
                Config::get('sumup.merchant_id') ?? null,
                Config::get('sumup.api_key') ?? null
            );
        });
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/sumup.php' => config_path('sumup.php'),
            ], 'sumup-config');
        }
    }
}
