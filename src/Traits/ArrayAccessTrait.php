<?php

namespace Cajudev\Classes\Traits;

trait ArrayAccessTrait
{
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->content[] = $value;
        } else {
            $this->content[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->content[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->content[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->content[$offset]) ? $this->content[$offset] : null;
    }
}