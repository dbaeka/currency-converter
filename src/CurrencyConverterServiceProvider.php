<?php

namespace Dbaeka\BuckhillCurrencyConverter;

use Dbaeka\BuckhillCurrencyConverter\Console\Commands\RefreshRates;
use Dbaeka\BuckhillCurrencyConverter\Services\Client;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class CurrencyConverterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (!app()->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__ . '/../config/curr_converter.php', 'curr_converter');
        }

        $this->app->singleton(Client::class, function () {
            return new Client(
                uri: config('curr_converter.url'),
                timeout: config('curr_converter.timeout'),
                retryTimes: config('curr_converter.retry_times'),
                retryMilliseconds: config('curr_converter.retry_milliseconds'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/curr_converter.php' => config_path('curr_converter.php'),
            ], 'curr-converter-config');

            $this->commands([
                RefreshRates::class,
            ]);
        }

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('exchange:update-rates')->dailyAt('07:00');
        });
    }
}
