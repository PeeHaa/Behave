<?php declare(strict_types = 1); // atom

namespace Netmosfera\BehaveTests\Verification\Interactions\Units;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use PHPUnit\Framework\TestCase;
use Netmosfera\Behave\Log\Interaction;
use Netmosfera\Behave\Log\SetInteraction;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use function Netmosfera\Behave\same;
use function Netmosfera\Behave\set;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class SetInteractionConstraintTest extends TestCase
{
    function test_cannot_fulfill_if_type_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new class() implements Interaction{};

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_object_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new SetInteraction("#", "@", "@", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_member_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new SetInteraction("@", "#", "@", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_assigned_object_constraint_cannot_be_fulfilled(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new SetInteraction("@", "@", "#", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_throw_constraint_cannot_be_fulfilled_1(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new SetInteraction("@", "@", "@", NULL);

        $constraint = set("@", "@", same("@"), same(new Error()), FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_throw_constraint_cannot_be_fulfilled_2(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new SetInteraction("@", "@", "@", $e = new Error());

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $constraint->fulfill($interactions);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_alone(){
        $interactions[] = new SetInteraction("@", "@", "@", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $result = $constraint->fulfill($interactions);

        self::assertSame([], $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    function test_fulfill_alone_with_throw(){
        $interactions[] = new SetInteraction("@", "@", "@", $e = new Error());

        $constraint = set("@", "@", same("@"), same($e), FALSE);
        $result = $constraint->fulfill($interactions);

        self::assertSame([], $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_position_0(){
        $interactions[] = new SetInteraction("@", "@", "@", NULL);
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-3, $result->continueIndex);
    }

    function test_fulfill_position_1(){
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("@", "@", "@", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 1, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-2, $result->continueIndex);
    }

    function test_fulfill_position_2(){
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("@", "@", "@", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 2, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_fulfill_position_3(){
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);
        $interactions[] = new SetInteraction("@", "@", "@", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 3, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_eat_position_0(){
        $interactions[] = new SetInteraction("@", "@", "@", NULL);
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-3, $result->continueIndex);
    }

    function test_fulfill_eat_position_1(){
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("@", "@", "@", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 2, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-2, $result->continueIndex);
    }

    function test_fulfill_eat_position_2(){
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("@", "@", "@", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 3, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_fulfill_eat_position_3(){
        $interactions[] = new SetInteraction("a", "a", "a", NULL);
        $interactions[] = new SetInteraction("b", "b", "b", NULL);
        $interactions[] = new SetInteraction("c", "c", "c", NULL);
        $interactions[] = new SetInteraction("@", "@", "@", NULL);

        $constraint = set("@", "@", same("@"), same(NULL), TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 4, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }
}
