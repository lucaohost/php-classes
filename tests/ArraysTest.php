<?php

use PHPUnit\Framework\TestCase;
use Cajudev\Classes\Arrays;
use Cajudev\Classes\Strings;

class ArraysTest extends TestCase
{

    public function test_creating_from_array()
    {
        $regularArray = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet,', 'consectetur', 'adipiscing', 'elit'];
        $arrays = new Arrays($regularArray);
        self::assertEquals($regularArray, $arrays->get());
    }

    public function test_creating_from_object()
    {
        $object = new class {
            private   $private = 'Lorem';
            public    $public = 'ipsum';
            protected $protected = 'dolor';
        };
        $arrays = new Arrays($object);
        $expect = ['private' => 'Lorem', 'public' => 'ipsum', 'protected' => 'dolor'];
        self::assertEquals($expect, $arrays->get());
    }

    public function test_pushing_several_values()
    {
        $arrays = new Arrays();

        $object = new class {
            private   $private = 'Lorem';
            public    $public = 'ipsum';
            protected $protected = 'dolor';
        };

        $array = ['amet' => 'consectetur'];

        $arrays->push('Lorem', $object, 'ipsum', $array);
        $expect = [
            'Lorem',
            'private'   => 'Lorem',
            'public'    => 'ipsum',
            'protected' => 'dolor',
            'ipsum',
            'amet'      => 'consectetur'
        ];
        self::assertEquals($expect, $arrays->get());
    }

    public function test_inserting_associative_values()
    {
        $arrays = new Arrays();

        $arrays->apush(
            'Lorem'     , 'ipsum',
            'dolor'     , 'sit',
            'amet,'     , 'consectetur',
            'adipiscing'
        );

        $expect = [
            'Lorem'      => 'ipsum',
            'dolor'      => 'sit',
            'amet,'      => 'consectetur',
            'adipiscing' => null
        ];

        self::assertEquals($expect, $arrays->get());
    }

    public function test_inserting_values_using_array_sintax()
    {
        $arrays = new Arrays();

        $arrays['Lorem']         = 'ipsum';
        $arrays[]                = 'dolor';
        $arrays['sit']['amet']   = 'amet';

        $expect = ['Lorem' => 'ipsum', 0 => 'dolor', 'sit' => ['amet' => 'amet']];
        
        self::assertEquals($expect, $arrays->get());
    }

    public function test_accessing_invalid_keys_should_return_null()
    {
        $arrays = new Arrays();
        self::assertEquals(null, $arrays['ipsum']);
    }

    public function test_isset_should_return_true()
    {
        $array = new Arrays();
        $array['Lorem'] = 'ipsum';
        self::assertEquals(true, isset($array['Lorem']));
    }

    public function test_isset_should_return_false()
    {
        $array = new Arrays();
        $array['Lorem'] = 'ipsum';
        self::assertEquals(false, isset($array['ipsum']));
    }

    public function test_unset_key()
    {
        $array = new Arrays();
        $array['Lorem'] = 'ipsum';
        unset($array['Lorem']);
        self::assertEquals(false, isset($array['Lorem']));
    }

    public function test_iterating_array_foreach()
    {
        $arrays = new Arrays('Lorem', null, 'ipsum', 'dolor');
        foreach ($arrays as $key => $value) {
            self::assertEquals($arrays[$key], $value);
        }
    }

    public function test_map()
    {
        $arrays = new Arrays('lorem', 'ipsum', 'dolor');
        $arrays->map(function($value) {
            $str = new Strings($value);
            return $str->upper()->get();
        });
        $expect = ['LOREM', 'IPSUM', 'DOLOR'];
        self::assertEquals($expect, $arrays->get());
    }

    public function test_kmap()
    {
        $arrays = new Arrays('lorem', 'ipsum', 'dolor');
        $arrays->kmap(function($key) {
            return ++$key;
        });
        $expect = [1 => 'lorem', 2 => 'ipsum', 3 => 'dolor'];
        self::assertEquals($expect, $arrays->get());
    }

    public function test_fmap()
    {
        $arrays = new Arrays('lorem', 'ipsum', 'dolor');

        $arrays->fmap(function($key) {
            return ++$key;
        }, function($value) {
            $str = new Strings($value);
            return $str->upper()->get();
        });

        $expect = [1 => 'LOREM', 2 => 'IPSUM', 3 => 'DOLOR'];
        self::assertEquals($expect, $arrays->get());
    }

    public function test_isArray_should_return_true()
    {
        self::assertEquals(true, Arrays::isArray([1,2,3,4,5]));
    }

    public function test_isArray_should_return_false()
    {
        self::assertEquals(false, Arrays::isArray('1,2,3,4,5'));
    }

    public function test_chunk()
    {
        $arrays = new Arrays([1, 2, 3, 4, 5]);
        $expect = [0 => [1, 2], 1 => [3, 4], 2 => [5]];
        self::assertEquals($expect, $arrays->chunk(2)->get());
    }

    public function test_combine()
    {
        $arrays = new Arrays();
        $arrays['KEYS']   = new Arrays('Lorem', 'ipsum');
        $arrays['VALUES'] = new Arrays('dolor', 'amet');
        $arrays = Arrays::combine($arrays['KEYS'], $arrays['VALUES']);
        $expect = ['Lorem' => 'dolor', 'ipsum'=> 'amet'];
        self::assertEquals($expect, $arrays->get());
    }

    public function test_count()
    {
        $arrays    = new Arrays(1, 2, 3, 4, 5);
        $arrays[2] = [1, 2, 3];

        self::assertEquals(5, $arrays->count());
        self::assertEquals(3, $arrays[2]->count());
    }

    public function test_last()
    {
        $arrays = new Arrays(1, 2, 3, 4, 5);
        self::assertEquals(5, $arrays->last());
    }

    public function test_keyCase()
    {
        $arrays = new Arrays();
        $arrays->apush('Hello', 5);
        
        $arrays->lower();
        self::assertEquals(['hello' => 5], $arrays->get());

        $arrays->upper();
        self::assertEquals(['HELLO' => 5], $arrays->get());
    }

    public function test_toString()
    {
        $arrays = new Arrays(['Lorem' => 1, 'Ipsum' => 2]);
        $expect = '{"Lorem":1,"Ipsum":2}';
        self::assertEquals($expect, $arrays);
    }
}