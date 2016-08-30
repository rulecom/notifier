<?php namespace RuleCom\Notifier\Channels;

use GuzzleHttp\Client;

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

    public function __construct(Client $client)
    {
        $this->client = $client;
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
        $this->client->post($this->endpoint, [
            'json' => [
                'channel' => $this->channel,
                'text' => $this->message
            ]
        ]);
    }
}
