<?php

namespace Cajudev\Classes;

class Json
{
    public static function encode($value, int $options = 0, int $depth = 512): string
    {
        return json_encode($value, $options, $depth);
    }

    public static function decode(string $json, $assoc = true, int $depth = 512, int $options = 0)
    {
        return json_decode($json, $assoc, $depth, $options);
    }
}