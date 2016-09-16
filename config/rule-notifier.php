<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rule API key
    |--------------------------------------------------------------------------
    |
    | Here you may specify your Rule API key. This i needed for sending email
    | transactions with Rule.
    |
    */
    'api_key' => 'YOUR_API_KEY_HERE',

    /*
    |--------------------------------------------------------------------------
    | Slack webhook
    |--------------------------------------------------------------------------
    |
    | Here you may specify which incoming webhook to use.
    |
    */
    'slack_endpoint' => 'https://hook...',

    /*
    |--------------------------------------------------------------------------
    | Debug Mode
    |--------------------------------------------------------------------------
    |
    | Here you can specify weather to set debug mode on channels.
    | If debug is set to true, all notification messages will be logged to file.
    | Specify log file path below.
    |
    */
    'debug' => false,
    'log_path' => 'path/to/your.log'
];
