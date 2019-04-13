<?php

namespace Cajudev;

use Cajudev\Util\Type;
use Cajudev\Util\Json;
use Cajudev\Exceptions\MalformedException;

final class Arrays extends Objects implements \ArrayAccess, \Iterator, \Countable
{
    use \Cajudev\Traits\ArrayAccessTrait;
    use \Cajudev\Traits\IteratorTrait;
    use \Cajudev\Traits\CountableTrait;

    private const BREAK    = 'break';
    private const CONTINUE = 'continue';

    private $length;

    public function __construct(array $content = [])
    {
        $this->content = $content;
        $this->count();
    }

    /**
     * Set the content of the array by reference
     *
     * @param  array $array
     *
     * @return self
     */
    public function setByReference(array &$array): self
    {
        $this->content =& $array;
        $this->count();
        return $this;
    }

    /**
     * Insert the values on the beginning of the array
     *
     * @param  mixed $values
     *
     * @return self
     */
    public function unshift(...$values): self
    {
        array_unshift($this->content, ...$values);
        $this->increment(count($values));
        return $this;
    }

    /**
     * Insert the values on the final of the array
     *
     * @param  mixed $values
     *
     * @return self
     */
    public function push(...$values): self
    {
        array_push($this->content, ...$values);
        $this->increment(count($values));
        return $this;
    }

    /**
     * Perform a simplified for loop
     *
     * @param  int     $i
     * @param  int     $add
     * @param  Closure $function
     *
     * @return void
     */
    public function for(int $i, int $add, \Closure $function)
    {
        $keys   = array_keys($this->content);
        $count  = count($this->content);

        for ($i; ($add >= 0 ? $i < $count : $i >= 0); $i += $add) {
            $return = $function($keys[$i], $this->content[$keys[$i]]);
            switch ($return) {
                case self::BREAK: break 2;
                case self::CONTINUE; continue 2;
            }
        }
    }

    /**
     * Perform a foreach loop
     *
     * @param  Closure $function
     *
     * @return void
     */
    public function each(\Closure $function)
    {
        foreach ($this->content as $key => $value) {
            $return = $function($key, $value);
            switch ($return) {
                case self::BREAK: break 2;
                case self::CONTINUE; continue 2;
            }
        }
    }

    /**
     * Applies the callback to all elements
     *
     * @param  callable $handle
     *
     * @return self
     */
    public function map(callable $handle): self
    {
        $keys = array_keys($this->content);
        $this->content = array_column(array_map($handle, $keys, $this->content), 1, 0);
        return $this;
    }

    /**
     * Determine if a key is set and it's value is not null
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function isset($key): bool
    {
        return isset($this[$key]);
    }

    /**
     * Determine if a key is not set
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function noset($key): bool
    {
        return !isset($this[$key]);
    }

    /**
     * Determine wheter a variable is empty
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function empty($key): bool
    {
        return empty($this[$key]);
    }

    /**
     * Determine wheter a variable is not empty
     *
     * @param  mixed $key
     *
     * @return bool
     */
    public function filled($key): bool
    {
        return !empty($this[$key]);
    }

    /**
     * Unset a given variable
     *
     * @param  mixed $key
     *
     * @return void
     */
    public function unset($key)
    {
        unset($this[$key]);
    }

    /**
     * Remove the first element from an array
     *
     * @return self
     */
    public function shift(): self
    {
        array_shift($this->content);
        $this->decrement();
        return $this;
    }

    /**
     * Remove the last element from an array
     *
     * @return self
     */
    public function pop(): self
    {
        array_pop($this->content);
        $this->decrement();
        return $this;
    }

    /**
     * Join array elements into a Strings Object
     *
     * @return void
     */
    public function join(string $glue)
    {
        return new Strings(implode($glue, $this->content));
    }

    /**
     * Return a object with all the keys of the array
     *
     * @return self
     */
    public function keys(): self
    {
        return new self(array_keys($this->content));
    }

    /**
     * Return a object with all the values of the array
     *
     * @return self
     */
    public function values(): self
    {
        return new self(array_values($this->content));
    }

    /**
     * Return the values from a single column
     *
     * @param  mixed $key
     * @param  mixed $index
     *
     * @return self
     */
    public function column($key, $index = null): ?self
    {
        return new self(array_column($this->content, $key));
    }
    
    /**
     * Split the array into chunks
     *
     * @param  int $size
     * @param  bool $preserve_keys
     *
     * @return self
     */
    public function chunk(int $size, bool $preserve_keys = false): self
    {
        $this->content = array_chunk($this->content, $size, $preserve_keys);
        $this->count();
        return $this;
    }

    /**
     * Return the last element of the array
     *
     * @return void
     */
    public function last()
    {
        $return = $this->end();
        $this->rewind();
        return $return;
    }

    /**
     * Change the case of all keys in the array to lower case
     *
     * @return self
     */
    public function lower(): self
    {
        $this->content = array_change_key_case($this->content, CASE_LOWER);
        return $this;
    }

    /**
     * Change the case of all keys in the array to upper case
     *
     * @return self
     */
    public function upper(): self
    {
        $this->content = array_change_key_case($this->content, CASE_UPPER);
        return $this;
    }

    /**
     * Get the value associated a given key or keys
     *
     * @param  mixed $keys
     *
     * @return mixed
     */
    public function get(...$keys)
    {
        if (!$keys) {
            return $this->content;
        }

        $array = [];
        foreach ($keys as $key) {
            $array = $array ? $array->offsetGet($key) : $this->offsetGet($key);
        }
        return $array;
    }

    /**
     * Increment the length property
     *
     * @return void
     */
    private function increment(int $value = 1) {
        $this->length += $value;
    }

    /**
     * Decrement the length property
     *
     * @return void
     */
    private function decrement(int $value = 1) {
        $this->length -= $value;
    }

    public function __get($property) {
        if ($property == 'length') {
            return $this->length;
        }
    }

    public function __toString()
    {
        return Json::encode($this->get(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /* ============== STATIC METHODS ============== */

    /**
     * Verify whether a element is an array
     *
     * @param  mixed $array
     *
     * @return bool
     */
    public static function isArray($array): bool
    {
        return is_array($array);
    }

    /**
     * Transform all properties of a object into an associative array
     *
     * @param  $object
     *
     * @return self
     */
    public static function fromObject($object): self
    {
        if (!is_object($object)) {
            return false;
        }

        $vars = new self((array) $object);
        return $vars->map(function($key, $value) {
            return [preg_replace('/.*\0(.*)/', '\1', $key), $value];
        });
    }

    /**
     * Combine two arrays, using the first for keys and the second for values
     *
     * @param  mixed $keys
     * @param  mixed $values
     *
     * @return self
     */
    public static function combine($keys, $values): self
    {
        if ($keys instanceof self) {
            $keys = $keys->get();
        }

        if ($values instanceof self) {
            $values = $values->get();
        }
        
        return new self(array_combine($keys, $values));
    }
}
