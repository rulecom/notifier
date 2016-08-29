<?php namespace RuleCom\Notifier;

use BadMethodCallException;
use Exception;

class Notifier
{
    /**
     * Dispatches notifications for all given channels
     *
     * @param object $notification
     * @throws NotificationFailed
     */
    public function dispatch($notification)
    {
        $channelNames = $notification->via();

        foreach ($channelNames as $name) {
            $channel = $this->getChannel($name, $notification);

            try {
                $channel->dispatch();
            } catch (Exception $e) {
                throw new NotificationFailed($e->getMessage());
            }
        }
    }

    /**
     * Get channel from notifications to{Channel} method
     *
     * @param string $name
     * @param object $notification
     *
     * @return Channel
     */
    private function getChannel($name, $notification)
    {
        $method = "to" . ucfirst($name);

        if (! method_exists($notification, $method)) {
            throw new BadMethodCallException("Method: {$method} does not exist");
        }

        return $notification->{$method}();
    }
}
