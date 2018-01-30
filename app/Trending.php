<?php

namespace App;

use \Redis;


class Trending
{
    public static function cacheKey()
    {
        return env('APP_ENV') == 'testing' ? 'test.trending_threads' : 'trending_threads';
    }

    public static function get()
    {
        return array_map('json_decode', Redis::zrevrange(static::cacheKey(), 0, 4));
    }

    public static function push($member)
    {
        Redis::zincrby(static::cacheKey(), 1, $member);
    }

    public static function reset()
    {
        Redis::del(static::cacheKey());
    }
}