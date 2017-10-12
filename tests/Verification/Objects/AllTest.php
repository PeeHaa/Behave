<?php declare(strict_types = 1); // atom

namespace Netmosfera\BehaveTests\Verification\Objects;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Netmosfera\Behave\Verification\Objects\NotObjectConstraint;
use PHPUnit\Framework\TestCase;
use function Netmosfera\Behave\any;
use function Netmosfera\Behave\same;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class AllTest extends TestCase
{
    function test_any(){
        self::assertTrue(any()->isFulfilledBy(1));
        self::assertTrue(any()->isFulfilledBy(true));
        self::assertTrue(any()->isFulfilledBy("foo"));
        self::assertTrue(any()->isFulfilledBy(new class(){}));
    }

    function test_same(){
        self::assertTrue(same(1)->isFulfilledBy(1));
        self::assertFalse(same(1)->isFulfilledBy(2));

        self::assertTrue(same(true)->isFulfilledBy(true));
        self::assertFalse(same(false)->isFulfilledBy(true));

        self::assertTrue(same("foo")->isFulfilledBy("foo"));
        self::assertFalse(same("bar")->isFulfilledBy("foo"));

        self::assertTrue(same($a = new class(){})->isFulfilledBy($a));
        self::assertFalse(same(new class(){})->isFulfilledBy(new class{}));
    }

    function test_not(){
        $not = function($o){ return new NotObjectConstraint($o); };

        self::assertFalse($not(same(1))->isFulfilledBy(1));
        self::assertTrue($not(same(1))->isFulfilledBy(2));

        self::assertFalse($not(same(true))->isFulfilledBy(true));
        self::assertTrue($not(same(false))->isFulfilledBy(true));

        self::assertFalse($not(same("foo"))->isFulfilledBy("foo"));
        self::assertTrue($not(same("bar"))->isFulfilledBy("foo"));

        self::assertFalse($not(same($a = new class(){}))->isFulfilledBy($a));
        self::assertTrue($not(same(new class(){}))->isFulfilledBy(new class{}));
    }
}
