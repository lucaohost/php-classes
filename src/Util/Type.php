<?php

namespace Cajudev\Util;

class Type
{
    const ARRAY    = 'array';
    const OBJECT   = 'object';
    const STRING   = 'string';
    const BOOLEAN  = 'boolean';
    const INTEGER  = 'integer';
    const DOUBLE   = 'double';
    const RESOURCE = 'resource';
    const NULL     = 'NULL';
    const UNKNOWN  = 'unknown type';

    private function __construct() {}

    /**
     * Get the type of a variable
     *
     * @return void
     */
    public static function getType($mixed)
    {
        return gettype($mixed);
    }

    public static function isArray($mixed) {
        return is_array($mixed);
    }

    public static function isString($mixed) {
        return is_string($mixed);
    }
}