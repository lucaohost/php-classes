<?php

namespace Cajudev\Classes;

/**
 * Responsible for manipulating JSON
 */
class Json
{
    /**
     * Returns the JSON Representation of a value
     *
     * @param  mixed $value
     * @param  int   $options
     * @param  int   $depth
     *
     * @return string
     */
    public static function encode($value, int $options = 0, int $depth = 512): string
    {
        return json_encode($value, $options, $depth);
    }

    /**
     * Decodes a JSON string
     *
     * @param  string $json
     * @param  bool   $assoc
     * @param  int    $depth
     * @param  int    $options
     *
     * @return void
     */
    public static function decode(string $json, $assoc = true, int $depth = 512, int $options = 0)
    {
        return json_decode($json, $assoc, $depth, $options);
    }
}