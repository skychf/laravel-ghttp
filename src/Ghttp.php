<?php

namespace Skychf\Ghttp;

class Ghttp
{
    public static function __callStatic($method, $args)
    {
        return GhttpRequest::getInstance()->{$method}(...$args);
    }
}
