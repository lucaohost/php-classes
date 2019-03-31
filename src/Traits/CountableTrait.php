<?php

namespace Cajudev\Classes\Traits;

trait CountableTrait
{
    /**
     * Count all elements of the array
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->content);
    }
}