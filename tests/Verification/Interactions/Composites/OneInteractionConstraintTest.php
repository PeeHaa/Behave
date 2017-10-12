<?php declare(strict_types = 1); // atom

namespace Netmosfera\BehaveTests\Verification\Interactions\Composites;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use PHPUnit\Framework\TestCase;
use Netmosfera\Behave\Log\GetInteraction;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use function Netmosfera\Behave\same;
use function Netmosfera\Behave\get;
use function Netmosfera\Behave\one;
use function array_values;
use function array_filter;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class OneInteractionConstraintTest extends TestCase
{
    private function remove(Array $from, Array $indexes): Array{
        $objects = [];
        foreach($indexes as $index){
            $objects[] = $from[$index];
        }
        $filtered = array_filter($from, function($object) use($objects){
            return !in_array($object, $objects, TRUE);
        });
        return array_values($filtered);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_throws_if_less_than_two_constraints_are_given_to_the_ctor(){
        $this->expectException(Error::CLASS);

        one([
            get("@", "@", same("@"), FALSE, FALSE)
        ], FALSE);
    }

    function test_removes_first_only(){
        $interactions[0] = new GetInteraction("°", "°", "°", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("§", "§", "§", FALSE);

        $constraint = one([
            get("@", "@", same("@"), FALSE, FALSE),
            get("§", "§", same("§"), FALSE, FALSE),
        ], FALSE);

        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [1]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_removes_first_only_reversed(){
        $interactions[0] = new GetInteraction("°", "°", "°", FALSE);
        $interactions[1] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[2] = new GetInteraction("§", "§", "§", FALSE);
        $interactions[3] = new GetInteraction("°", "°", "°", FALSE);

        $constraint = one([
            get("§", "§", same("§"), FALSE, FALSE),
            get("@", "@", same("@"), FALSE, FALSE),
        ], FALSE);

        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_remove_one(){
        $interactions[0] = new GetInteraction("§", "§", "§", FALSE);

        $constraint = one([
            get("@", "@", same("@"), FALSE, FALSE),
            get("§", "§", same("§"), FALSE, FALSE),
        ], FALSE);

        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    function test_throw(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[0] = new GetInteraction("§", "§", "§", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("y", "y", "y", FALSE);

        $constraint = one([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
            get("x", "x", same("x"), FALSE, FALSE),
        ], FALSE);

        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    function test_eat(){
        $interactions[0] = new GetInteraction("§", "§", "§", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("y", "y", "y", FALSE);

        $constraint = one([
            get("@", "@", same("@"), FALSE, FALSE),
            get("#", "#", same("#"), FALSE, FALSE),
            get("x", "x", same("x"), FALSE, FALSE),
        ], TRUE);

        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_eat_all(){
        $interactions[0] = new GetInteraction("§", "§", "§", FALSE);
        $interactions[1] = new GetInteraction("*", "*", "*", FALSE);
        $interactions[2] = new GetInteraction("#", "#", "#", FALSE);
        $interactions[3] = new GetInteraction("y", "y", "y", FALSE);

        $constraint = one([
            get("@", "@", same("@"), FALSE, FALSE),
            get("y", "y", same("y"), FALSE, FALSE),
            get("x", "x", same("x"), FALSE, FALSE),
        ], TRUE);

        $result = $constraint->fulfill($interactions);

        $interactions = $this->remove($interactions, [0, 1, 2, 3]);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }
}
