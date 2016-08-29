<?php namespace RuleCom\Notifier\Channels;

interface Channel
{
    /**
     * Dispatch notification message
     */
    public function dispatch();
}