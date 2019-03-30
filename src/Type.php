<?php

namespace Cajudev\Classes;

/**
 * Responsible for manager primitive types
 */
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