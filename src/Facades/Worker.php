<?php


namespace Luka\Envoy\Facades;


use Illuminate\Support\Facades\Facade;

class Worker extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'envoy-worker';
    }
}