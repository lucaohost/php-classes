<?php

namespace Cajudev;

class Objects
{
    protected $content;

    public static function instanceOf($object): bool
    {
        return $object instanceof static;
    }
}