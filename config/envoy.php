<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Full path to laravel envoy
    |--------------------------------------------------------------------------
    |
    | Make sure that user running your PHP script has access to this
    |
    | Default: '~/.composer/vendor/bin/envoy'
    |
    */

    'path' => env('ENVOY_PATH', '~/.composer/vendor/bin/envoy'),

    /*
    |--------------------------------------------------------------------------
    | Working directory
    |--------------------------------------------------------------------------
    |
    | This should be location where your Envoy.blade.php is located. Usually
    | this is root project directory, which we can get with base_path helper.
    |
    | Default: base_path();
    |
    */

    'directory' => base_path(),

    /*
    |--------------------------------------------------------------------------
    | Command execution timeout
    |--------------------------------------------------------------------------
    |
    | https://symfony.com/doc/current/components/process.html#process-timeout
    |
    | Default: 3600
    |
    */

    'timeout' => 3600,

    /*
    |--------------------------------------------------------------------------
    | Idle timeout
    |--------------------------------------------------------------------------
    |
    | Process is considered timed out if there is no output for X seconds
    |
    | https://symfony.com/doc/current/components/process.html#process-idle-timeout
    |
    | Default: (seconds) 300
    |
    */

    'idle_timeout' => 300,

    /*
    |--------------------------------------------------------------------------
    | Force console output
    |--------------------------------------------------------------------------
    |
    | If command execution on remote server fails, when set to false, it will
    |
    | return an exception and if true, it will return console output
    |
    | Default: false
    |
    */

    'force_output' => true,

    /*
    |--------------------------------------------------------------------------
    | An array of regexes to clear output from console command
    |
    | Works only when force_output => true
    |
    |--------------------------------------------------------------------------
    |
    */

    'clear_output' => [
        '/\[✗] This task did not complete successfully on one of your servers\./'
    ]

];
