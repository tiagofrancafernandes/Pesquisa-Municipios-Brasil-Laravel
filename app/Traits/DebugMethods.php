<?php

namespace App\Traits;

trait DebugMethods
{
    protected static bool $debugMode = \false;

    public static function debug(?bool $debug = \false): bool
    {
        return static::$debugMode = $debug ?? static::$debugMode;
    }
}
