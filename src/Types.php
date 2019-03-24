<?php

namespace PHPClass;

class Types
{
    private $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function getType()
    {
        return gettype($this->object);
    }
}