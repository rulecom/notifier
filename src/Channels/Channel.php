<?php namespace RuleCom\Notifier\Channels;

interface Channel
{
    public function dispatch();
}