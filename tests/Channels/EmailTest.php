<?php namespace RuleCom\Notifier\Test\Channels;

use GuzzleHttp\Client;
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
        $emailChannel = new Email($guzzleMock->reveal(), 'dummy-api-key');
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
                    'html' => '<p>Test content</p>',
                    'plain' => 'Test content'
                ]
            ]
        ])->shouldHaveBeenCalled();
    }
}
