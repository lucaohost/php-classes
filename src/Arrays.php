<?php

namespace Cajudev\Classes;

use Cajudev\Classes\Type;
use Cajudev\Classes\Json;
use Cajudev\Classes\Exceptions\MalformedException;

class Arrays implements \ArrayAccess, \Iterator, \Countable
{
    use \Cajudev\Classes\Traits\ArrayAccessTrait;
    use \Cajudev\Classes\Traits\IteratorTrait;

    private $content = [];

    public function __construct(...$content)
    {
        $this->push(...$content);
    }

    /**
     * Transform all properties of a object into an associative array
     *
     * @param  Object $object
     *
     * @return array
     */
    private function parseObject(Object $object): array
    {
        $vars = (array) $object;
        $array = [];
        foreach ($vars as $name => $value) {
            $nameString = new Strings($name);
            $nameString->xreplace('/.*\0(.*)/', '\1');
            $array[$nameString->get()] = $value;
        }
        return $array;
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
        foreach ($values as $value) {
            $type = new Type($value);

            switch ($type->getType()) {
                case Type::NULL:
                    continue 2;
                case Type::ARRAY:
                    $this->content += $value;
                    break;
                case Type::OBJECT:
                    $this->content += $this->parseObject($value);
                    break;
                default:
                    $this->content[] = $value;
            }
        }

        return $this;
    }

    /**
     * Insert the associative key and values on the final of the array
     *
     * @param  mixed $values
     *
     * @return self
     */
    public function apush(...$values): self
    {
        $values = new Arrays($values);

        for ($i = 0; $i < $values->count(); $i++) {
            $this->content[$values[$i]] = $values[++$i] ?? null;
        }

        return $this;
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
     * Count all elements of the array
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->content);
    }

    /**
     * Return the last element of the array
     *
     * @return void
     */
    public function last()
    {
        return $this->content[$this->count() - 1] ?? null;
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

    public function get(): array
    {
        return $this->content;
    }

    public function __toString()
    {
        return Json::encode($this->content, JSON_UNESCAPED_SLASHES);
    }

    /* ============== STATIC METHODS ============== */

    /**
     * Verify whether a element is instance of Arrays
     *
     * @param  mixed $array
     *
     * @return bool
     */
    public static function isArrays($array): bool
    {
        return $array instanceof Arrays;
    }

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
     * Combine two arrays, using the first for keys and the second for values
     *
     * @param  mixed $keys
     * @param  mixed $values
     *
     * @return self
     */
    public static function combine($keys, $values): self
    {
        $keys   = Arrays::isArrays($keys)   ? $keys->get()   : $keys;
        $values = Arrays::isArrays($values) ? $values->get() : $values;
        
        return new Arrays(array_combine($keys, $values));
    }
}