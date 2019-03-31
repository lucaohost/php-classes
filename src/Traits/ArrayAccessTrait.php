<?php

namespace Cajudev\Classes\Traits;

trait ArrayAccessTrait
{
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->content[] = $value;
        } else {
            $this->content[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->content[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->content[$offset]);
    }

    public function &offsetGet($key)
    {
        $ret =& $this->content[$key];
        $ret = self::isArray($ret) ? new self($ret) : $ret;
        return $ret;
    }
}