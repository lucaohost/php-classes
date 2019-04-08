<?php

namespace Cajudev\Classes\Traits;

use Cajudev\Classes\Strings;
use Cajudev\Classes\Arrays;

trait ArrayAccessTrait
{
    /**
     * Set a value in array
     *
     * @param  mixed $key
     * @param  mixed $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (Arrays::instanceOf($value)) {
            $value = $value->get();
        }

        if ($key === null) {
            return $this->content[] = $value;
        }

        $path = (new Strings($key))->split('.');
        $this->recursiveOffsetSet($this->content, $path->get(), $value);
    }

    /**
     * Set value in array recursively when using dot notation
     *
     * @param  mixed $array
     * @param  mixed $keys
     * @param  mixed $value
     * @param  mixed $i
     *
     * @return void
     */
    private function recursiveOffsetSet(&$array, $keys, $value, $i = 0) {
        if (empty($keys[$i + 1])) {
            return $array[$keys[$i]] = $value;
        } 
        $newArray =& $array[$keys[$i]];
        $this->recursiveOffsetSet($newArray, $keys, $value, ++$i);   
    }

    /**
     * Check if a key is set
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function offsetExists($key): bool
    {
        $path = (new Strings($key))->split('.');

        if ($path->count() > 1) {
            return $this->recursiveOffsetExists($this->content, $path->get());
        }

        return isset($this->content[$key]);
    }

    /**
     * Check if a key is set recursively when using dot notation
     *
     * @param  mixed $array
     * @param  mixed $keys
     * @param  mixed $i
     *
     * @return bool
     */
    private function recursiveOffsetExists(&$array, $keys, $i = 0): bool
    {
        if (empty($keys[$i + 1])) {
            return isset($array[$keys[$i]]);
        }

        $newArray =& $array[$keys[$i]];
        return $this->recursiveOffsetExists($newArray, $keys, ++$i);
    }

    /**
     * Unset a value in array
     *
     * @param  mixed $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        $path = (new Strings($key))->split('.');

        if ($path->count() > 1) {
            $this->recursiveOffsetUnset($this->content, $path->get());
        } else {
            unset($this->content[$key]);
        }
    }

    /**
     * Unset a value in array recursively when using dot notation
     *
     * @param  mixed $array
     * @param  mixed $keys
     * @param  mixed $i
     *
     * @return void
     */
    private function recursiveOffsetUnset(&$array, $keys, $i = 0)
    {
        if (empty($keys[$i + 1])) {
            unset($array[$keys[$i]]);
        } else {
            $newArray =& $array[$keys[$i]];
            $this->recursiveOffsetUnset($newArray, $keys, ++$i);
        }
    }

    /**
     * Get a value from the array
     *
     * @param  mixed $key
     *
     * @return mixed
     */
    public function &offsetGet($key)
    {
        $path = (new Strings((string)$key))->split('.');

        if ($path->count() > 1) {
            $return = $this->recursiveOffsetGet($this->content, $path->get());
            return $return;
        }
        
        $content =& $this->content[$key];
        
        if (Arrays::isArray($content)) {
            $return = new Arrays();
            $return->setByReference($content);
            return $return;
        }

        return $content;
    }

    /**
     * Get a value from the array recursively when using dot notation
     *
     * @param  mixed $array
     * @param  mixed $keys
     * @param  mixed $i
     *
     * @return mixed
     */

    private function recursiveOffsetGet(&$array, $keys, $i = 0)
    {
        if (empty($keys[$i])) {
            $return = new Arrays();
            return Arrays::isArray($array) ? $return->setByReference($array) : $array;
        }
        $newArray =& $array[$keys[$i]];
        return $this->recursiveOffsetGet($newArray, $keys, ++$i);
    }
}