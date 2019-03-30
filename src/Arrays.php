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

    public static function isArray($array): bool
    {
        return is_array($array);
    }

    public static function combine($keys, $values): self
    {
        $keys   = $keys   instanceof Arrays ? $keys->get()   : $keys;
        $values = $values instanceof Arrays ? $values->get() : $values;
        
        return new self(array_combine($keys, $values));
    }

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

    public function apush(...$values): self
    {
        $values = new Arrays($values);

        for ($i = 0; $i < $values->count(); $i++) {
            $this->content[$values[$i]] = $values[++$i] ?? null;
        }

        return $this;
    }

    
    public function chunk(int $size, bool $preserve_keys = false): self
    {
        $this->content = array_chunk($this->content, $size, $preserve_keys);
        return $this;
    }

    public function count(): int
    {
        return count($this->content);
    }

    public function last()
    {
        return $this->content[$this->count() - 1] ?? null;
    }

    public function lower(): self
    {
        $this->content = array_change_key_case($this->content, CASE_LOWER);
        return $this;
    }

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
}