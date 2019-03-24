<?php

namespace PHPClass;

use PHPClass\Arrays;
use PHPClass\Regexs;

class Strings
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function replace($search, $replace, $regex = false, int $limit = -1, &$count = 0)
    {
        if ($regex) {
            $regex = new Regexs($search);
            return $regex->replace($replace, $this->string, $limit, $count);
        }

        $this->string = str_replace($search, $replace, $this->string, $count);
        return $this;
    }

    public function split(string $pattern, bool $regex = false, int $limit = PHP_INT_MAX, int $flags = 0): Arrays
    {
        if ($regex) {
            $regex = new Regexs($pattern);
            return $regex->split($this->string, $limit, $flags);
        }

        return new Arrays(explode($pattern, $this->string, $limit));
    }

    public function getString(): string
    {
		return $this->string;
	}

    public function setString(string $string)
    {
        $this->string = $string;
        return $this;
    }
    
    public function __toString()
    {
        return $this->string;
    }
}