# Rule notifier

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Send notifications via Rule (email/text message) and Slack. Inspired by Laravels notification system and can be used
with the Laravel framework or completely indipendent.

## Install

Via Composer

``` bash
$ composer require rulecom/notifier
```

## Usage

To send notification you need to create notification objects. These objects are responsible for telling the Notifier via which channels the notification message should be sent through and what each corresponding channel message should contain.


```php
use RuleCom\Notifier\Channels\Email;
use RuleCom\Notifier\Channels\Slack;

class UserHasRegistered
{
    /**
     * Here we specify through which channels we want to
     * send our notification.
    */
    public function via()
    {
        return ['email', 'slack'];
    }

    /**
     * Each via method needs a correspondng "to" method.
    */
    public function toEmail()
    {
        // Specify what the email message should contain.
    }

    /**
     * Each via method needs a correspondng "to" method.
    */
    public function toSlack()
    {
        // Specify what the Slack message should contain.
    }
}

// To send the notification to all specified channels:
$notifier = new RuleCom\Notifier\Notifier();
$notifier->send(new UserHasRegistered());
```

### Channels

Currently this package supports the following channel providers:

* [Rule](https://rule.se) for sending email and text messages.
* [Slack](https://slack.com) for sending messages to Slack.

#### Email via (Rule):

``` php
public function toEmail()
{
    return (new RuleCom\Notifier\Channels\Email(new GuzzleHttp\Client()))
        ->apikey('YOUR-RULE-API-KEY') // If using Laravel you can set this in config/rule-notifier.php
        ->subject('Hello, world!')
        ->from([
            'name' => 'John Doe',
            'email' => 'john@doe.com'
        ])
        ->to([
            'name' => 'Jane Doe',
            'email' => 'jane@doe.com'
        ])
        ->content([
            'html' => '<h1>Notification sent via Rule!</h1>',
            'html' => 'Notification sent via Rule!'
        ]);
}
```

#### Slack:
``` php
public function toSlack()
{
    return (new RuleCom\Notifier\Channels\Slack(new GuzzleHttp\Client()))
        ->endpoint('YOUR-SLACK-INCOMING-WEBHOOK') // If using Laravel you can set this in config/rule-notifier.php
        ->channel('#notification') // Here you can override the channel specified in Slack, or send DM by passing @username
        ->message('Hello, world!');
}
```

### Usage with Laravel

This package can be easily integrated with laravel, with the following benefits.

* No need to pass in channel dependecies on your own.
* Ability to specify configurations such as, api key for Rule and webhook for Slack.

1. In your `config/app.php` add the following service provider
``` php
RuleCom\Notifier\LaravelServiceProvider::class
```

2. Publish the config:
``` bash
php artisan vendor:publish
```

``` php
// Without Laravel you will have to pass the channel dependency on your own:
(new RuleCom\Notifier\Channels\Slack(new GuzzleHttp\Client()))

// With Laravel you can resolve the channels with dependencies through the ioc container:
app(RuleCom\Notifier\Channels\Slack::class)
```

### Debugging

If you need to debug a channel you may set it to debug mode. When a channel is in debug mode it will log the notification
instead of dispatching the it to given channel.

To enable debug:

1. Inject `Monolog\Logger` into the channel.
2. Call the `debug` method and pass in a path to your logfile.

```php
return (new RuleCom\Notifier\Channels\Slack(new GuzzleHttp\Client(), new Monolog\Logger('Notification logger')))
        ->debug('path/to/file.log') // If using Laravel you can set both debug mode and log path in config/rule-notifier.php
        ->endpoint('YOUR-SLACK-INCOMING-WEBHOOK') // If using Laravel you can set this in config/rule-notifier.php
        ->channel('#notification') // Here you can override the channel specified in Slack, or send DM by passing @username
        ->message('Hello, world!');
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email matthis.stenius@rule.se instead of using the issue tracker.

## Credits

- [Matthis Stenius][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rulecom/notifier.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rulecom/notifier/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rulecom/notifier.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rulecom/notifier.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rulecom/notifier.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rulecom/notifier
[link-travis]: https://travis-ci.org/rulecom/notifier
[link-scrutinizer]: https://scrutinizer-ci.com/g/rulecom/notifier/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rulecom/notifier
[link-downloads]: https://packagist.org/packages/rulecom/notifier
[link-author]: https://github.com/matthisstenius
[link-contributors]: ../../contributors
