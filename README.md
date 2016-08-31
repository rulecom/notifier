# Rule notifier

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

**Note:** Replace ```:author_name``` ```:author_username``` ```:author_website``` ```:author_email``` ```:vendor``` ```:package_name``` ```:package_description``` with their correct values in [README.md](README.md), [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](CONTRIBUTING.md), [LICENSE.md](LICENSE.md) and [composer.json](composer.json) files, then delete this line.

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
    **/
    public function via()
    {
        return ['email', 'slack'];
    }

    /**
     * Each via method needs a correspondng to method.
    **/
    * this is where we specify how the message should be built.
    public function toEmail()
    {
        // Here you specify how the email message should look.
    }

    /**
     * Each via method needs a correspondng to method.
    **/
    * this is where we specify how the message should be built.
    public function toSlack()
    {
        // Here you specify how the slack message should look.
    }
}

// To send the notification to all specified channels:
$notifier = new RuleCom\Notifier\Notifier();
$notifier->send(new UserHasRegistered());
```

### Channels

Currently this package supports sending emails via [Rule][https://rule.se] and messages to [Slack][https://slack.com]

#### Email:

``` php
public function toEmail()
{
    return new (RuleCom\Notifier\Channels\Email(new GuzzleHttp\Client()))
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
    return new (RuleCom\Notifier\Channels\Slack(new GuzzleHttp\Client()))
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
// Without Laravel you will have to pass the channel dependency:
new (RuleCom\Notifier\Channels\Slack(new GuzzleHttp\Client()))

// With Laravel you can resolve the channels with dependencies through the ioc container:
app(RuleCom\Notifier\Channels\Slack::class)
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

[ico-version]: https://img.shields.io/packagist/v/:vendor/:package_name.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/:vendor/:package_name/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/:vendor/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/:vendor/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/:vendor/:package_name.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/:vendor/:package_name
[link-travis]: https://travis-ci.org/:vendor/:package_name
[link-scrutinizer]: https://scrutinizer-ci.com/g/:vendor/:package_name/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/:vendor/:package_name
[link-downloads]: https://packagist.org/packages/:vendor/:package_name
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors
