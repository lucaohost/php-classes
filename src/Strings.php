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

    public function replace(string $search, string $replacement, $regex = false, int $limit = -1, &$count = 0)
    {
        return $regex ? $this->regexReplace($search, $replacement, $limit, $count) : $this->regularReplace($search, $replacement, $count);
    }

    private function regularReplace(string $search, string $replacement, &$count = 0)
    {
        $this->string = str_replace($search, $replacement, $this->string, $count);
        return $this->string;
    }

    private function regexReplace(string $search, string $replacement, int $limit = -1, &$count = 0)
    {
        $regex = new Regexs($search);
        $this->string = $regex->replace($replacement, $this->string, $limit, $count);
        return $this->string;
    }

    public function split(string $pattern, bool $regex = false, int $limit = PHP_INT_MAX, int $flags = 0): Arrays
    {
        return $regex ? $this->regexSplit($pattern, $limit, $flags) : $this->regularSplit($pattern, $limit);
    }

    private function regularSplit(string $delimiter, int $limit = PHP_INT_MAX): Arrays
    {
        return new Arrays(explode($delimiter, $this->string, $limit));
    }

    private function regexSplit(string $pattern, int $limit = PHP_INT_MAX, int $flags = 0): Arrays
    {
        $regex = new Regexs($pattern);
        return $regex->split($this->string, $limit, $flags);
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