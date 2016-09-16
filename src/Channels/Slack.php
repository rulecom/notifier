<?php namespace RuleCom\Notifier\Channels;

use GuzzleHttp\Client;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class Slack implements Channel
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $channel;

    /**
     * @var string
     */
    private $message;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $logPath;

    /**
     * @var bool
     */
    private $debug = false;

    public function __construct(Client $client, Logger $logger = null)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function debug($logPath)
    {
        $this->debug = true;
        $this->logPath = $logPath;
        return $this;
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function endpoint($endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param string $channel
     * @return $this
     */
    public function channel($channel)
    {
        $this->channel = $channel;
        return $this;
    }

    /**
     * @param string $message
     * @return $this
     */
    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Dispatch notification message
     */
    public function dispatch()
    {
        if ($this->debug) {
            return $this->fakeDispatch();
        }

        $this->client->post($this->endpoint, [
            'json' => [
                'channel' => $this->channel,
                'text' => $this->message
            ]
        ]);
    }

    /**
     * Fakes dispatch by logging instead
     */
    private function fakeDispatch()
    {
        $this->logger->pushHandler(new StreamHandler($this->logPath));
        $this->logger->addInfo('Slack:', ['endpoint' => $this->endpoint, 'message' => $this->message]);
    }
}
