<?php

namespace PHPClass;

use PHPClass\Types;

class Arrays implements \Iterator
{
    const TYPE_ARRAY  = 'array';
    const TYPE_OBJECT = 'object';

    private $array;

    public function __construct($content)
    {
        $this->setArray($content);
    }

    public function setArray($content)
    {
        $type = new Types($content);
        switch ($type->getType()) {
            case self::TYPE_ARRAY:
                $this->array = $content;
                break;
            case self::TYPE_OBJECT:
                $this->array = $this->parseObject($content);
                break;
            default:
                throw new TypeError('The argument passed it\'s not an array or an object');
        }
    }

    private function parseObject(Object $object)
    {
        $vars = (array) $object;
        $array = [];
        foreach ($vars as $name => $value) {
            $nameString = new Strings($name);
            $nameArray  = $nameString->split("\0");
            $array[$nameArray->getLast()] = $value;
        }
        return $array;
    }

    public function count(): int
    {
        return count($this->array);
    }

    public function getArray(): array
    {
        return $this->array;
    }

    public function getFirst()
    {
        return $this->array[0] ?? null;
    }

    public function getLast()
    {
        return $this->array[$this->count() - 1] ?? null;
    }

    public static function isArray($array): bool
    {
        return is_array($array);
    }

    /* ================================================== */
    /* ================= ITERATOR METHODS =============== */
    /* ================================================== */

    public function rewind()
    {
        reset($this->array);
    }

    public function current()
    {
        return current($this->array);
    }

    public function key()
    {
        return key($this->array);
    }

    public function next()
    {
        return next($this->array);
    }

    public function valid()
    {
        $key = key($this->array);
        return $key !== null && $key !== false;
    }
}