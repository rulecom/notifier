<?php namespace RuleCom\Notifier;

use Illuminate\Support\ServiceProvider;
use Rule\ApiWrapper\Api\V2\Transaction\Transaction;
use Rule\ApiWrapper\ApiFactory;
use RuleCom\Notifier\Channels\Email;

class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(Email::class)
            ->needs(Transaction::class)
            ->give(function ($app) {
                return ApiFactory::make($app['config']['rule']['api_key'], 'transaction');
            });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/rule.php' => config_path('rule.php'),
        ]);
    }
}