<?php namespace RuleCom\Notifier\Test\Channels;

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPUnit_Framework_TestCase;
use RuleCom\Notifier\Channels\Slack;

class SlackTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_have_dispatch_message_to_slack()
    {
        $guzzleMock = $this->prophesize(Client::class);

        $slackChannel = new Slack($guzzleMock->reveal());

        $slackChannel->channel('#test-channel')
            ->endpoint('https://dummy-endpoint')
            ->message('Test message')
            ->dispatch();

        $guzzleMock->post('https://dummy-endpoint', [
            'json' => [
                'text' => 'Test message',
                'channel' => '#test-channel'
            ]
        ])->shouldHaveBeenCalled($guzzleMock);
    }

    /**
     * @test
     */
    public function it_should_log_message_if_debug_is_enabled()
    {
        $guzzleMock = $this->prophesize(Client::class);
        $monologMock = $this->prophesize(Logger::class);

        $slackChannel = new Slack($guzzleMock->reveal(), $monologMock->reveal());

        $slackChannel->channel('#test-channel')
            ->debug('path/to/log')
            ->endpoint('https://dummy-endpoint')
            ->message('Test message')
            ->dispatch();

        $monologMock->pushHandler(new StreamHandler('path/to/log'))
            ->shouldHaveBeenCalled();

        $monologMock->info('Slack:', ['endpoint' => 'https://dummy-endpoint', 'message' => 'Test message'])
            ->shouldHaveBeenCalled();
    }
}
