<?php

namespace Cajudev\Classes\Traits;

trait ArrayAccessTrait
{
    public function offsetSet($offset, $value)
    {
        $value = self::isArray($value) ? new self($value) : $value;
        if ($offset === null) {
            $this->content[] = $value;
        } else {
            $this->content[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return $this->isset($offset);
    }

    public function offsetUnset($offset)
    {
        $this->unset($offset);
    }

    public function &offsetGet($key)
    {
        $ret =& $this->content[$key];
        $ret = self::isArray($ret) ? new self($ret) : $ret;
        return $ret;
    }
}