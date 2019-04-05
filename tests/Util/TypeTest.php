<?php

use PHPUnit\Framework\TestCase;
use Cajudev\Classes\Util\Type;

class TypeTest extends TestCase
{
    public function test_get_type_string()
    {
        $string = 'lorem ipsum';
        self::assertEquals(Type::STRING, Type::getType($string));
    }

    public function test_get_type_integer()
    {
        $int = 158;
        self::assertEquals(Type::INTEGER, Type::getType($int));
    }

    public function test_get_type_double()
    {
        $double = 158.35;
        self::assertEquals(Type::DOUBLE, Type::getType($double));
    }

    public function test_get_type_array()
    {
        $array = [1, 2, 3];
        self::assertEquals(Type::ARRAY, Type::getType($array));
    }

    public function test_get_type_object()
    {
        $object = new StdClass;
        self::assertEquals(Type::OBJECT, Type::getType($object));
    }

    public function test_get_type_null()
    {
        $null = null;
        self::assertEquals(Type::NULL, Type::getType($null));
    }

    public function test_get_type_boolean()
    {
        $bool = true;
        self::assertEquals(Type::BOOLEAN, Type::getType($bool));
    }
}