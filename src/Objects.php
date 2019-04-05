<?php

namespace Cajudev\Classes;

class Objects
{
    protected $content;

    public static function instanceOf($object): bool
    {
        return $object instanceof static;
    }
}