<?php namespace RuleCom\Notifier;

use Guzzle\Http\Client;
use Illuminate\Support\ServiceProvider;
use Rule\ApiWrapper\Api\V2\Transaction\Transaction;
use Rule\ApiWrapper\ApiFactory;
use RuleCom\Notifier\Channels\Email;
use RuleCom\Notifier\Channels\Slack;

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
            ->needs(Client::class)
            ->give(function ($app) {
                $channel = new Email(new Client);
                $channel->apiKey($app['config']['rule-notifier']['api_key']);
                return $channel;
            });

        $this->app->when(Slack::class)
            ->needs(Client::class)
            ->give(function ($app) {
                $channel = new Slack(new Client);
                $channel->endpoint($app['config']['rule-notifier']['slack_endpoint']);
                return $channel;
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
            __DIR__.'/../config/rule-notifier.php' => config_path('rule-notifier.php'),
        ]);
    }
}