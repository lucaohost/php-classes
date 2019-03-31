<?php

use PHPUnit\Framework\TestCase;
use Cajudev\Classes\Type;

class TypeTest extends TestCase
{
    public function test_get_type_string()
    {
        $string = 'lorem ipsum';
        $type = new Type($string);
        self::assertEquals(Type::STRING, $type->getType());
    }

    public function test_get_type_integer()
    {
        $int = 158;
        $type = new Type($int);
        self::assertEquals(Type::INTEGER, $type->getType());
    }

    public function test_get_type_double()
    {
        $double = 158.35;
        $type = new Type($double);
        self::assertEquals(Type::DOUBLE, $type->getType());
    }

    public function test_get_type_array()
    {
        $array = [1, 2, 3];
        $type = new Type($array);
        self::assertEquals(Type::ARRAY, $type->getType());
    }

    public function test_get_type_object()
    {
        $object = new Type('lorem');
        $type = new Type($object);
        self::assertEquals(Type::OBJECT, $type->getType());
    }

    public function test_get_type_null()
    {
        $null = null;
        $type = new Type($null);
        self::assertEquals(Type::NULL, $type->getType());
    }

    public function test_get_type_boolean()
    {
        $bool = true;
        $type = new Type($bool);
        self::assertEquals(Type::BOOLEAN, $type->getType());
    }
}