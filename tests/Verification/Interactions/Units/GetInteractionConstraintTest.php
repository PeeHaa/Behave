<?php declare(strict_types = 1); // atom

namespace Netmosfera\BehaveTests\Verification\Interactions\Units;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use function Netmosfera\Behave\same;
use function Netmosfera\Behave\get;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use Netmosfera\Behave\Log\GetInteraction;
use Netmosfera\Behave\Log\Interaction;
use PHPUnit\Framework\TestCase;
use Error;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class GetInteractionConstraintTest extends TestCase
{
    function test_cannot_fulfill_if_type_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new class() implements Interaction{};

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_object_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new GetInteraction("#", "@", "@", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_member_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new GetInteraction("@", "#", "@", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_expects_throw_gets_return(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new GetInteraction("@", "@", $e = new Error(), FALSE);

        $constraint = get("@", "@", same($e), TRUE, TRUE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_expects_return_gets_throw(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new GetInteraction("@", "@", $e = new Error(), FALSE);

        $constraint = get("@", "@", same($e), TRUE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_result_constraint_cannot_be_fulfilled(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new GetInteraction("@", "@", "#", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_alone(){
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        self::assertSame([], $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_position_0(){
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-3, $result->continueIndex);
    }

    function test_fulfill_position_1(){
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 1, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-2, $result->continueIndex);
    }

    function test_fulfill_position_2(){
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 2, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_fulfill_position_3(){
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 3, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_eat_position_0(){
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-3, $result->continueIndex);
    }

    function test_fulfill_eat_position_1(){
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 2, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-2, $result->continueIndex);
    }

    function test_fulfill_eat_position_2(){
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 3, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_fulfill_eat_position_3(){
        $interactions[] = new GetInteraction("a", "a", "a", FALSE);
        $interactions[] = new GetInteraction("b", "b", "b", FALSE);
        $interactions[] = new GetInteraction("c", "c", "c", FALSE);
        $interactions[] = new GetInteraction("@", "@", "@", FALSE);

        $constraint = get("@", "@", same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 4, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }
}
