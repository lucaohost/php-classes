<?php

namespace Cajudev\Classes;

class Objects
{
    protected $content;

    public function instanceOf($object): bool
    {
        return $object instanceof static;
    }
}