<?php namespace RuleCom\Notifier\Test;

use Exception;
use PHPUnit_Framework_TestCase;
use RuleCom\Notifier\Channels\TestChannel;
use RuleCom\Notifier\Notifier;
use RuleCom\Notifier\Test\Fixtures\TestNotification;

class NotifierTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_dispatch_notification_to_corresponding_channel()
    {
        $notifier = new Notifier();
        $notificationMock = $this->prophesize(TestNotification::class);
        $channelMock = $this->prophesize(TestChannel::class);

        $notificationMock->via()
            ->willReturn(['test']);

        $notificationMock->toTest()
            ->willReturn($channelMock->reveal());

        $notifier->send($notificationMock->reveal());

        $channelMock->dispatch()
            ->shouldHaveBeenCalled();
    }

    /**
     * @test
     * @expectedException BadMethodCallException
     */
    public function it_should_throw_exception_if_corresponding_to_channel_method_does_not_exist()
    {
        $notifier = new Notifier();
        $notificationMock = $this->prophesize(TestNotification::class);

        $notificationMock->via()
            ->willReturn(['fail']);

        $notifier->send($notificationMock->reveal());
    }

    /**
     * @test
     * @expectedException RuleCom\Notifier\NotificationFailed
     */
    public function it_should_handle_channel_exceptions()
    {
        $notifier = new Notifier();
        $notificationMock = $this->prophesize(TestNotification::class);
        $channelMock = $this->prophesize(TestChannel::class);

        $notificationMock->via()
            ->willReturn(['test']);

        $notificationMock->toTest()
            ->willReturn($channelMock->reveal());

        $channelMock->dispatch()
            ->willThrow(Exception::class);

        $notifier->send($notificationMock->reveal());
    }
}
