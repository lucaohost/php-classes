<?php

namespace Cajudev\Classes;

use Cajudev\Classes\Util\Type;
use Cajudev\Classes\Util\Json;
use Cajudev\Classes\Exceptions\MalformedException;

class Arrays extends Objects implements \ArrayAccess, \Iterator, \Countable
{
    use \Cajudev\Classes\Traits\ArrayAccessTrait;
    use \Cajudev\Classes\Traits\IteratorTrait;
    use \Cajudev\Classes\Traits\CountableTrait;

    private const BREAK    = 'break';
    private const CONTINUE = 'continue';

    public function __construct(...$content)
    {
        if (count($content) == 1) {
            $this->set($content[0]);
        } else {
            $this->content = $content;
        }
    }

    /**
     * Set the content of the array
     *
     * @param  mixed $content
     *
     * @return self
     */
    public function set($content): self
    {
        switch (Type::getType($content)) {
            case Type::ARRAY:
                $this->prepare($content);
                $this->content = $content;
                break;
            case Type::OBJECT:
                $this->content = $this->parseObject($content);
                break;
        }

        return $this;
    }

    /**
     * Convert all arrays to Arrays objects
     *
     * @param  mixed $array
     *
     * @return void
     */
    private function prepare(array &$array)
    {
        foreach ($array as $key => $value) {
            if (Arrays::isArray($value)) {
                $array[$key] = new Arrays($value);
            }
        }
    }

    /**
     * Transform all properties of a object into an associative array
     *
     * @param  Object $object
     *
     * @return array
     */
    private function parseObject($object): array
    {
        $vars = new Arrays((array) $object);
        return $vars->kmap(function($key) {
            $key = new Strings($key);
            return $key->xreplace('/.*\0(.*)/', '\1')->get();
        })->get();
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
        $keys   = $this->keys();
        $values = $this->values();
        $count  = $values->count();

        for ($i; ($add >= 0 ? $i < $count : $i >= 0); $i += $add) {
            $return = $function($keys[$i], $values[$i]);

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
        $this->content = array_map(function($var) use($handle) {  
            return $handle($var);
        }, $this->content);

        return $this;
    }

    /**
     * Applies the callback to all keys
     *
     * @param  callable $handle
     *
     * @return self
     */
    public function kmap(callable $handle): self
    {
        $keys = $this->keys()->map($handle);
        $values = $this->values();

        $this->content = Arrays::combine($keys, $values)->get();
        return $this;
    }

    /**
     * Applies the callback to both, keys and values
     *
     * @param  callable $keyHandle
     * @param  callable $valueHandle
     *
     * @return self
     */
    public function fmap(callable $keyHandle, callable $valueHandle): self
    {
        $keys = $this->keys()->map($keyHandle);
        $values = $this->values()->map($valueHandle);

        $this->content = Arrays::combine($keys, $values)->get();
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
        return new Arrays(array_keys($this->content));
    }

    /**
     * Return a object with all the values of the array
     *
     * @return self
     */
    public function values(): self
    {
        return new Arrays(array_values($this->content));
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
        $ret = [];
        foreach ($this->content as $content) {
            if (($var = $content[$key]) !== null) {
                $ret[] = $var;
            }
        }
        return $ret ? new Arrays($ret) : null;
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
        $ret = [];
        $keys = new self($keys);
        if (($c = $keys->count()) > 0) {
            $ret = $this->content[$keys[0]] ?? null;
            for ($i = 1; $i < $c; $i++) {
                $ret = $ret[$keys[$i]] ?? null;
            }
            $ret = self::isArray($ret) ? new self($ret) : $ret;
        } else {
            foreach ($this->content as $key => $content) {
                $ret[$key] = Arrays::instanceOf($content) ? $content->get() : $content;
            }
        }
        return $ret;
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
        return Type::isArray($array);
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
        $keys   = Arrays::instanceOf($keys)   ? $keys->get()   : $keys;
        $values = Arrays::instanceOf($values) ? $values->get() : $values;
        
        return new Arrays(array_combine($keys, $values));
    }
}
