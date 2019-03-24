<?php

namespace PHPClass;

use PHPClass\Arrays;

class Strings
{
    private $string;

    public function __construct(string $string)
    {
        $this->string = $string;
    }

    public function split(string $delimiter, int $limit = PHP_INT_MAX): Arrays
    {
        return new Arrays(explode($delimiter, $this->string, $limit));
    }
}