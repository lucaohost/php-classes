<?php

use PHPUnit\Framework\TestCase;
use Cajudev\Classes\Json;

class JsonTest extends TestCase
{
    public function test_encode()
    {
        $lorem = ['Lorem' => 'ipsum', 'dolor' => 'sit', 'amet' => 'consectetur'];
        $json = Json::encode($lorem);
        $expect = '{"Lorem":"ipsum","dolor":"sit","amet":"consectetur"}';
        self::assertEquals($expect, $json);
    }

    public function test_decode_in_array()
    {
        $lorem = '{"Lorem":"ipsum","dolor":"sit","amet":"consectetur"}';
        $json = Json::decode($lorem);
        $expect = ['Lorem' => 'ipsum', 'dolor' => 'sit', 'amet' => 'consectetur'];
        self::assertEquals($expect, $json);
    }

    public function test_decode_in_object()
    {
        $lorem = '{"Lorem":"ipsum","dolor":"sit","amet":"consectetur"}';
        $json = Json::decode($lorem, false);
        $expect = new stdClass;
        $expect->Lorem = 'ipsum';
        $expect->dolor = 'sit';
        $expect->amet  = 'consectetur';
        self::assertEquals($expect, $json);
    }
}