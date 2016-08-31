<?php namespace RuleCom\Notifier;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
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
        $emailChannel = new Email(new Client);
        $emailChannel->apiKey($this->app['config']['rule-notifier']['api_key']);
        $this->app->instance(Email::class, $emailChannel);

        $slackChannel = new Slack(new Client);
        $slackChannel->endpoint($this->app['config']['rule-notifier']['slack_endpoint']);
        $this->app->instance(Slack::class, $slackChannel);
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
