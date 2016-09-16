<?php namespace RuleCom\Notifier;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
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
        $debugEnabled = $this->app['config']['rule-notifier']['debug'];

        $emailChannel = new Email(new Client, new Logger('Notification logger'));
        $emailChannel->apiKey($this->app['config']['rule-notifier']['api_key']);

        if ($debugEnabled) {
            $emailChannel->debug($this->app['config']['rule-notifier']['log_path']);
        }

        $this->app->instance(Email::class, $emailChannel);

        $slackChannel = new Slack(new Client, new Logger('Notification logger'));
        $slackChannel->endpoint($this->app['config']['rule-notifier']['slack_endpoint']);

        if ($debugEnabled) {
            $slackChannel->debug($this->app['config']['rule-notifier']['log_path']);
        }

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
            __DIR__.'/../config/rule-notifier.php' => $this->app->basePath() . '/config/rule-notifier.php',
        ]);
    }
}
