<?php namespace RuleCom\Notifier\Test\Channels;

use Guzzle\Http\Client;
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
}
