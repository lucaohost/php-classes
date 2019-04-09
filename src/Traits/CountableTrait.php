<?php

namespace Cajudev\Traits;

trait CountableTrait
{
    private $length;

    /**
     * Count all elements of the array
     *
     * @return int
     */
    public function count(int $mode = COUNT_NORMAL): int
    {
        return count($this->content, $mode);
    }

        /**
     * Count all elements of the array
     *
     * @return int
     */
    public function countOne(int $mode = COUNT_NORMAL): int
    {
        if ($this->length === null) {
            $this->length = count($this->content, $mode);
        }
        return $this->length;
    }
}