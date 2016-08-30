<?php namespace RuleCom\Notifier\Test\Channels;

use PHPUnit_Framework_TestCase;
use Rule\ApiWrapper\Api\V2\Transaction\Transaction;
use RuleCom\Notifier\Channels\Email;

class EmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_dispatch_email_to_rule_service()
    {
        $ruleApiMock = $this->prophesize(Transaction::class);
        $emailChannel = new Email($ruleApiMock->reveal());
        $emailChannel->subject('Test subject')
            ->from(['name' => 'Tester', 'email' => 'tester@tester.com'])
            ->to(['name' => 'Tester', 'email' => 'other@tester.com'])
            ->content(['html' => '<p>Test content</p>', 'plain' => 'Test content'])
            ->dispatch();

        $ruleApiMock->send([
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
        ])->shouldHaveBeenCalled();
    }
}
