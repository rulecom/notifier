<?php namespace RuleCom\Notifier\Channels;

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Email implements Channel
{
    /**
     * @var Client
     */
    private $guzzle;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $subject = '';

    /**
     * @var array
     */
    private $from = ['name' => '', 'email' => ''];

    /**
     * @var array
     */
    private $to = ['name' => '', 'email' => ''];

    /**
     * @var array
     */
    private $content = ['html' => '', 'plain' => ''];

    /**
     * @var bool
     */
    private $debug = false;

    /**
     * @var string
     */
    private $logPath;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Client $guzzle, Logger $logger = null)
    {
        $this->guzzle = $guzzle;
        $this->logger = $logger;
    }

    /**
     * @param string $apiKey
     * @return $this
     */
    public function apiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param array $from
     * @return $this
     */
    public function from(array $from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * @param array $to
     * @return $this
     */
    public function to(array $to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @param array $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;
        return $this;
    }

    public function debug($logPath)
    {
        $this->debug = true;
        $this->logPath = $logPath;
        return $this;
    }
    /**
     * Dispatches notification message to Rule
     */
    public function dispatch()
    {
        if ($this->debug) {
            return $this->fakeDispatch();
        }

        foreach ($this->extractRecipients() as $recipient) {
            $this->guzzle->post('https://app.rule.io/api/v2/transactionals', [
                'json' => [
                    'apikey' => $this->apiKey,
                    'transaction_type' => 'email',
                    'transaction_name' => $this->subject,
                    'subject' => $this->subject,
                    'from' => $this->from,
                    'to' => $recipient,
                    'content' => [
                        'html' => base64_encode($this->content['html']),
                        'plain' => base64_encode($this->content['plain'])
                    ]
                ]
            ]);
        }
    }

    /**
     * @return array
     */
    private function extractRecipients()
    {
        if (is_array(reset($this->to))) {
            return $this->to;
        }

        return [$this->to];
    }

    /**
     * Fakes dispatch by logging instead
     */
    private function fakeDispatch()
    {
        $this->logger->pushHandler(new StreamHandler($this->logPath));
        $this->logger->info('Email:', [
            'from' => $this->from,
            'to' => $this->extractRecipients(),
            'subject' => $this->subject,
            'content' => $this->content
        ]);
    }
}
