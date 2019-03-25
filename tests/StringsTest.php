<?php

use PHPUnit\Framework\TestCase;
use PHPClass\Strings;
use PHPClass\Arrays;

class StringsTest extends TestCase
{
    public function setUp(): void
    {
        $string = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit';
        $this->strings = new Strings($string);
    }

    public function test_replace()
    {
        $this->strings->replace(' ', '');
        $expect = 'Loremipsumdolorsitamet,consecteturadipiscingelit';
        self::assertEquals($expect, $this->strings->getString());
    }

    public function test_replace_with_regex()
    {
        $this->strings->replace('/\s/', '', true);
        $expect = 'Loremipsumdolorsitamet,consecteturadipiscingelit';
        self::assertEquals($expect, $this->strings->getString());
    }

    public function test_split_should_return_instance_of_arrays()
    {
        $arrays = $this->strings->split("\s");
        self::assertInstanceOf(Arrays::class, $arrays);
    }

    public function test_split()
    {
        $arrays = $this->strings->split(" ");
        $expect = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet,', 'consectetur', 'adipiscing', 'elit'];
        self::assertEquals($expect, $arrays->getArray());
    }

    public function test_split_with_regex()
    {
        $arrays = $this->strings->split("/\s/", true);
        $expect = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet,', 'consectetur', 'adipiscing', 'elit'];
        self::assertEquals($expect, $arrays->getArray());
    }
}