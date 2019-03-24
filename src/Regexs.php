<?php

namespace PHPClass;

class Regexs
{
    private $regex;

    public function __construct(string $regex)
    {
        $this->regex = $regex;
    }

    public function split(string $subject, int $limit = -1, int $flags = 0): Arrays
    {
        return new Arrays(preg_split($this->regex, $subject, $limit, $flags));
    }

    public function replace(string $replacement, string $subject, int $limit = -1, &$count = 0): Arrays
    {
        return preg_replace($this->regex, $replacement, $subject, $limit, $count);
    }
    
    public function __toString()
    {
        return $this->regex;
    }
}