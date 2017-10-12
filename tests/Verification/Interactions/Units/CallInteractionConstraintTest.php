<?php declare(strict_types = 1); // atom

namespace Netmosfera\BehaveTests\Verification\Interactions\Units;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

use Error;
use PHPUnit\Framework\TestCase;
use Netmosfera\Behave\Log\Interaction;
use Netmosfera\Behave\Log\CallInteraction;
use Netmosfera\Behave\Verification\Interactions\CannotFulfill;
use function Netmosfera\Behave\same;
use function Netmosfera\Behave\call;

//[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

class CallInteractionConstraintTest extends TestCase
{
    function test_cannot_fulfill_if_type_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new class() implements Interaction{};

        $constraint = call(function(){}, [same("@")], same("@"), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_the_closure_is_different(){
        $this->expectException(CannotFulfill::CLASS);

        $interactions[] = new CallInteraction(function(){}, [], NULL, FALSE);

        $constraint = call(function(){}, [], same(NULL), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_arguments_count_does_not_match(){
        $this->expectException(CannotFulfill::CLASS);

        $c = function(){};
        $interactions[] = new CallInteraction($c, [555, 666], NULL, FALSE);

        $constraint = call($c, [same(666)], same(NULL), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_arguments_do_not_match(){
        $this->expectException(CannotFulfill::CLASS);

        $c = function(){};
        $interactions[] = new CallInteraction($c, [555], NULL, FALSE);

        $constraint = call($c, [same(666)], same(NULL), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_expect_throw_but_got_return(){
        $this->expectException(CannotFulfill::CLASS);

        $c = function(){};
        $interactions[] = new CallInteraction($c, [], $e = new Error(), FALSE);

        $constraint = call($c, [], same($e), TRUE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_expect_return_but_got_throw(){
        $this->expectException(CannotFulfill::CLASS);

        $c = function(){};
        $interactions[] = new CallInteraction($c, [], $e = new Error(), TRUE);

        $constraint = call($c, [], same($e), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    function test_cannot_fulfill_if_result_does_not_match(){
        $this->expectException(CannotFulfill::CLASS);

        $c = function(){};
        $interactions[] = new CallInteraction($c, [], "return1", FALSE);

        $constraint = call($c, [], same("return2"), FALSE, FALSE);
        $constraint->fulfill($interactions);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_alone(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        self::assertSame([], $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_position_0(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-3, $result->continueIndex);
    }

    function test_fulfill_position_1(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 1, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-2, $result->continueIndex);
    }

    function test_fulfill_position_2(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 2, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_fulfill_position_3(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, FALSE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 3, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }

    //[][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][][]

    function test_fulfill_eat_position_0(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 1, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-3, $result->continueIndex);
    }

    function test_fulfill_eat_position_1(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 2, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-2, $result->continueIndex);
    }

    function test_fulfill_eat_position_2(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 3, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(-1, $result->continueIndex);
    }

    function test_fulfill_eat_position_3(){
        $c = function(){};
        $interactions[] = new CallInteraction($c, ["a"], "a", FALSE);
        $interactions[] = new CallInteraction($c, ["b"], "b", FALSE);
        $interactions[] = new CallInteraction($c, ["c"], "c", FALSE);
        $interactions[] = new CallInteraction($c, ["@"], "@", FALSE);

        $constraint = call($c, [same("@")], same("@"), FALSE, TRUE);
        $result = $constraint->fulfill($interactions);

        array_splice($interactions, 0, 4, []);

        self::assertSame($interactions, $result->interactions);
        self::assertSame(0, $result->continueIndex);
    }
}
