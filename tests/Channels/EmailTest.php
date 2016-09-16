<?php namespace RuleCom\Notifier\Test\Channels;

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit_Framework_TestCase;
use RuleCom\Notifier\Channels\Email;

class EmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_dispatch_email_to_rule_service()
    {
        $guzzleMock = $this->prophesize(Client::class);
        $emailChannel = new Email($guzzleMock->reveal());
        $emailChannel->subject('Test subject')
            ->apiKey('dummy-api-key')
            ->from(['name' => 'Tester', 'email' => 'tester@tester.com'])
            ->to(['name' => 'Tester', 'email' => 'other@tester.com'])
            ->content(['html' => '<p>Test content</p>', 'plain' => 'Test content'])
            ->dispatch();

        $guzzleMock->post('https://app.rule.io/api/v2/transactionals', [
            'json' => [
                'apikey' => 'dummy-api-key',
                'transaction_type' => 'email',
                'transaction_name' => 'Test subject',
                'subject' => 'Test subject',
                'from' => [
                    'name' => 'Tester',
                    'email' => 'tester@tester.com'
                ],
                'to' => [
                    'name' => 'Tester',
                    'email' => 'other@tester.com'
                ],
                'content' => [
                    'html' => base64_encode('<p>Test content</p>'),
                    'plain' => base64_encode('Test content')
                ]
            ]
        ])->shouldHaveBeenCalled();
    }

    /**
     * @test
     */
    public function it_should_dispatch_multiple_email_to_rule_service()
    {
        $guzzleMock = $this->prophesize(Client::class);
        $emailChannel = new Email($guzzleMock->reveal());
        $emailChannel->subject('Test subject')
            ->apiKey('dummy-api-key')
            ->from(['name' => 'Tester', 'email' => 'tester@tester.com'])
            ->to([
                    [
                        'name' => 'John Doe',
                        'email' => 'john@doe.com'
                    ],
                    [
                        'name' => 'Jane Doe',
                        'email' => 'jane@doe.com'
                    ]
                ])
            ->content(['html' => '<p>Test content</p>', 'plain' => 'Test content'])
            ->dispatch();

        $guzzleMock->post('https://app.rule.io/api/v2/transactionals', [
            'json' => [
                'apikey' => 'dummy-api-key',
                'transaction_type' => 'email',
                'transaction_name' => 'Test subject',
                'subject' => 'Test subject',
                'from' => [
                    'name' => 'Tester',
                    'email' => 'tester@tester.com'
                ],
                'to' => [
                    'name' => 'John Doe',
                    'email' => 'john@doe.com'
                ],
                'content' => [
                    'html' => base64_encode('<p>Test content</p>'),
                    'plain' => base64_encode('Test content')
                ]
            ]
        ])->shouldHaveBeenCalled();

        $guzzleMock->post('https://app.rule.io/api/v2/transactionals', [
            'json' => [
                'apikey' => 'dummy-api-key',
                'transaction_type' => 'email',
                'transaction_name' => 'Test subject',
                'subject' => 'Test subject',
                'from' => [
                    'name' => 'Tester',
                    'email' => 'tester@tester.com'
                ],
                'to' => [
                    'name' => 'Jane Doe',
                    'email' => 'jane@doe.com'
                ],
                'content' => [
                    'html' => base64_encode('<p>Test content</p>'),
                    'plain' => base64_encode('Test content')
                ]
            ]
        ])->shouldHaveBeenCalled();
    }

    /**
     * @test
     */
    public function it_should_log_message_if_debug_is_enabled()
    {
        $guzzleMock = $this->prophesize(Client::class);
        $monologMock = $this->prophesize(Logger::class);
        $emailChannel = new Email($guzzleMock->reveal(), $monologMock->reveal());

        $emailChannel->subject('Test subject')
            ->debug('path/to/log')
            ->from(['name' => 'Tester', 'email' => 'tester@tester.com'])
            ->to(['name' => 'Tester', 'email' => 'other@tester.com'])
            ->content(['html' => '<p>Test content</p>', 'plain' => 'Test content'])
            ->dispatch();

        $monologMock->pushHandler(new StreamHandler('path/to/log'))
            ->shouldHaveBeenCalled();

        $monologMock->addInfo('Email:', [
            'subject' => 'Test subject',
            'from' => ['name' => 'Tester', 'email' => 'tester@tester.com'],
            'to' => [['name' => 'Tester', 'email' => 'other@tester.com']],
            'content' => ['html' => '<p>Test content</p>', 'plain' => 'Test content']
        ])->shouldHaveBeenCalled();
    }
}
