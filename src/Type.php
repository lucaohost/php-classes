<?php

namespace Cajudev\Classes;

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

    /**
     * Get the type of a variable
     *
     * @return void
     */
    public function getType()
    {
        return gettype($this->object);
    }
}