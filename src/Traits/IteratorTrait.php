<?php

namespace Cajudev\Classes\Traits;

trait IteratorTrait
{
    public function rewind()
    {
        reset($this->content);
    }

    public function current()
    {
        return current($this->content);
    }

    public function key()
    {
        return key($this->content);
    }

    public function next()
    {
        return next($this->content);
    }

    public function valid()
    {
        $key = key($this->content);
        return $key !== null && $key !== false;
    }
}